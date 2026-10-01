const express = require('express');
const { default: makeWASocket, useMultiFileAuthState, DisconnectReason } = require('@whiskeysockets/baileys');
const pino = require('pino');
const path = require('path');
const fs = require('fs');

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3000;
const sessions = new Map();

// Generate / Get QR for tenant
app.get('/api/qr/:tenantId', async (req, res) => {
    const tenantId = req.params.tenantId;
    const sessionDir = path.join(__dirname, 'auth_info_sessions', `session_${tenantId}`);
    
    if (!fs.existsSync(sessionDir)) {
        fs.mkdirSync(sessionDir, { recursive: true });
    }

    const { state, saveCreds } = await useMultiFileAuthState(sessionDir);
    const sock = makeWASocket({
        auth: state,
        logger: pino({ level: 'silent' }),
        printQRInTerminal: false,
    });

    sessions.set(tenantId, sock);
    sock.ev.on('creds.update', saveCreds);

    let qrSent = false;
    sock.ev.on('connection.update', (update) => {
        const { connection, qr, lastDisconnect } = update;
        if (qr && !qrSent) {
            qrSent = true;
            return res.json({ status: 'WAITING_SCAN', qr: qr });
        }
        if (connection === 'close') {
            const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut;
            if (shouldReconnect) {
                console.log(`Reconnecting session for tenant ${tenantId}...`);
            }
        } else if (connection === 'open') {
            console.log(`Tenant ${tenantId} WhatsApp Connected! Phone: ${sock.user?.id}`);
        }
    });

    // Timeout fallback if QR doesn't generate in 10s
    setTimeout(() => {
        if (!qrSent && !res.headersSent) {
            res.json({ status: 'INITIALIZING', message: 'Menunggu inisialisasi socket WhatsApp...' });
        }
    }, 10000);
});

// Send message endpoint
app.post('/api/send-message', async (req, res) => {
    const { session, to, text } = req.body;
    const tenantId = session.replace('tenant_', '');
    const sock = sessions.get(tenantId);

    if (!sock) {
        return res.status(400).json({ status: 'error', message: 'Sesi WhatsApp tidak aktif. Silakan scan ulang QR.' });
    }

    try {
        const result = await sock.sendMessage(to, { text: text });
        res.json({ status: 'success', messageId: result.key.id });
    } catch (err) {
        res.status(500).json({ status: 'error', message: err.message });
    }
});

app.listen(PORT, () => {
    console.log(`MWIFI Baileys WhatsApp Service running on http://127.0.0.1:${PORT}`);
});
