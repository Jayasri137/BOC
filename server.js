console.log("--- STARTING BLUESTONE BACKEND ---");
const express = require('express');
const axios = require('axios');
const mysql = require('mysql2/promise');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const cors = require('cors');
const nodemailer = require('nodemailer');
const fs = require("fs");
const multer = require("multer");
const path = require("path");
const { getMediaFileCategory, compressImageIfNeeded } = require('./mediaCompression');
const app = express();

// Multer Config for Documents
const docDir = path.join(__dirname, "uploads/documents");
if (!fs.existsSync(docDir)) {
    fs.mkdirSync(docDir, { recursive: true });
}
const storageDocuments = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, "uploads/documents");
    },
    filename: (req, file, cb) => {
        const unique = Date.now() + "-" + file.originalname;
        cb(null, unique);
    }
});
const uploadDocuments = multer({
    storage: storageDocuments,
    limits: { fileSize: 7 * 1024 * 1024 }, // 7 MB
    fileFilter: (req, file, cb) => {
        const allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv'
        ];
        const ext = path.extname(file.originalname).toLowerCase();
        const allowedExtensions = ['.pdf', '.jpeg', '.jpg', '.png', '.webp', '.gif', '.doc', '.docx', '.ppt', '.pptx', '.xls', '.xlsx', '.csv', '.txt'];

        if (allowedMimeTypes.includes(file.mimetype) || allowedExtensions.includes(ext)) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only PDF, Images, Word, PowerPoint, Excel, and Text files are allowed.'));
        }
    }
});

// Multer Config for Staff Documents
const staffDocDir = path.join(__dirname, "uploads/staff_docs");
if (!fs.existsSync(staffDocDir)) {
    fs.mkdirSync(staffDocDir, { recursive: true });
}
const storageStaffDocs = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, "uploads/staff_docs");
    },
    filename: (req, file, cb) => {
        const unique = Date.now() + "-" + file.originalname;
        cb(null, unique);
    }
});
const uploadStaffDocs = multer({
    storage: storageStaffDocs,
    limits: { fileSize: 1 * 1024 * 1024 }, // 1 MB limit
    fileFilter: (req, file, cb) => {
        const allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp'
        ];
        if (allowedTypes.includes(file.mimetype)) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only PDF and images are allowed.'));
        }
    }
});

// Multer Config for Media Gallery
const mediaDir = path.join(__dirname, "uploads/media");
if (!fs.existsSync(mediaDir)) {
    fs.mkdirSync(mediaDir, { recursive: true });
}
const storageMedia = multer.diskStorage({
    destination: (req, file, cb) => cb(null, "uploads/media"),
    filename: (req, file, cb) => {
        const unique = Date.now() + "-" + file.originalname.replace(/\s+/g, '-');
        cb(null, unique);
    }
});
const uploadMedia = multer({
    storage: storageMedia,
    limits: { fileSize: 200 * 1024 * 1024 }, // 200 MB hard cap (enforced per-category in route)
    fileFilter: (req, file, cb) => {
        const mime = (file.mimetype || '').toLowerCase();
        const name = (file.originalname || '').toLowerCase();
        const imageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg', 'image/x-png', 'image/svg+xml'];
        const videoTypes = ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo', 'video/ogg', 'video/x-m4v'];
        const docTypes = ['application/pdf', 'application/octet-stream'];
        const allowedTypes = [...imageTypes, ...videoTypes, ...docTypes];
        const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg', '.mp4', '.webm', '.mov', '.avi', '.pdf'];
        const hasAllowedMime = allowedTypes.includes(mime);
        const hasAllowedExtension = allowedExtensions.some(ext => name.endsWith(ext));

        if (hasAllowedMime || hasAllowedExtension) {
            cb(null, true);
        } else {
            cb(new Error('Invalid file type. Only Images (JPG/PNG/WEBP/GIF), Videos (MP4/WEBM/MOV), and PDFs are allowed.'));
        }
    }
});
// Increase limits to handle Base64 images
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ limit: '50mb', extended: true }));
app.use(cors({
    origin: (origin, callback) => {
        const allowedOrigins = [
            'http://localhost:5173',
            'http://127.0.0.1:5173',
            'https://bluestonegroupofinstitutions.com',
            'https://www.bluestonegroupofinstitutions.com',
            'http://bluestonegroupofinstitutions.com'
        ];

        if (!origin || allowedOrigins.some(o => origin.startsWith(o))) {
            callback(null, true);
        } else {
            callback(new Error('Not allowed by CORS'));
        }
    },
    credentials: true
}));
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));
const JWT_SECRET = 'bg_secret_2026';
// SMTP config (direct code config, no .env)
const SMTP_CONFIG = {
    host: 'smtp.gmail.com',
    port: 587,
    secure: false, // true for 465
    user: 'bluestonesoftwaredeveloper@gmail.com',
    pass: 'hwmo dxbr oiub mpfr',
    from: 'Bluestone 360 <bluestoneocs@gmail.com>'
};
// --- 1. DATABASE CONNECTION ---
const pool = mysql.createPool({
    // host: 'auth-db1278.hstgr.io',
    host: 'srv696.hstgr.io', // Resolved from auth-db1278.hstgr.io to bypass DNS issues
    user: 'u287260207_bgoi_user',
    password: '4g@LMW2026',
    database: 'u287260207_bgoi_bg',
    connectTimeout: 30000,
    waitForConnections: true,
    connectionLimit: 5,
    queueLimit: 50,
    enableKeepAlive: true,
    keepAliveInitialDelay: 10000,
    timezone: '+05:30'
});

// Centralized Query Helper to ensure connection reuse
async function query(sql, params = []) {
    const [rows] = await pool.execute(sql, params);
    return rows;
}

async function recordLeadEditHistory(leadId, fieldName, oldValue, newValue, changedBy, actionType = 'LEAD_EDIT') {
    const normalize = value => {
        if (value === undefined || value === null) return '';
        return typeof value === 'object' ? JSON.stringify(value) : String(value);
    };
    const oldText = normalize(oldValue);
    const newText = normalize(newValue);
    if (oldText === newText) return;

    await pool.execute(
        `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [leadId, actionType, fieldName, oldText, newText, changedBy || 'System', getISTString()]
    );
}

async function ensureMediaGalleryColumns() {
    try {
        const [columns] = await pool.query("SHOW COLUMNS FROM media_gallery LIKE 'month'");
        if (columns.length === 0) {
            await pool.query("ALTER TABLE media_gallery ADD COLUMN month VARCHAR(50) DEFAULT NULL");
        }
    } catch (err) {
        console.error('Media gallery column check failed:', err.message);
    }
}

async function ensureOverseasColumns() {
    try {
        const newColumns = [
            'passport_no',
            'university_student_id',
            'university_name',
            'course_name',
            'primary_status',
            'secondary_status'
        ];

        for (const col of newColumns) {
            const [columns] = await pool.query(`SHOW COLUMNS FROM leads LIKE '${col}'`);
            if (columns.length === 0) {
                await pool.query(`ALTER TABLE leads ADD COLUMN ${col} VARCHAR(255) DEFAULT NULL`);
                console.log(`Added missing column ${col} to leads table`);
            }
        }

        // Add College Contacts JSON column
        const [ccCols] = await pool.query(`SHOW COLUMNS FROM leads LIKE 'college_contacts'`);
        if (ccCols.length === 0) {
            await pool.query(`ALTER TABLE leads ADD COLUMN college_contacts JSON DEFAULT NULL`);
            console.log(`Added missing column college_contacts to leads table`);
        }
    } catch (err) {
        console.error('Overseas columns check failed:', err.message);
    }
}

ensureMediaGalleryColumns().catch(() => { });
ensureOverseasColumns().catch(() => { });

async function ensureRefundColumns() {
    try {
        const newColumns = [
            { name: 'refund_status', type: "VARCHAR(50) DEFAULT 'Not Applicable'" },
            { name: 'refund_amount', type: "DECIMAL(10, 2) DEFAULT 0.00" },
            { name: 'refund_date', type: "DATETIME NULL" },
            { name: 'refund_remarks', type: "TEXT NULL" }
        ];

        for (const col of newColumns) {
            const [columns] = await pool.query(`SHOW COLUMNS FROM leads LIKE '${col.name}'`);
            if (columns.length === 0) {
                await pool.query(`ALTER TABLE leads ADD COLUMN ${col.name} ${col.type}`);
                console.log(`Added missing column ${col.name} to leads table`);
            }
        }
    } catch (err) {
        console.error('Refund columns check failed:', err.message);
    }
}
ensureRefundColumns().catch(() => { });

// Proactive Error Monitor
pool.on('error', (err) => {
    console.error('[DATABASE POOL ERROR]:', err.message);
    if (err.code === 'PROTOCOL_CONNECTION_LOST') {
        console.warn('Reconnecting lost database connection...');
    }
});

// --- IST HELPERS (Strictly +05:30) ---
function getISTDate() {
    const d = new Date();
    // Normalize to UTC then add 330 mins
    const utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    return new Date(utc + (330 * 60000));
}

function getISTString() {
    const d = getISTDate();
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const seconds = String(d.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function getISTDay() {
    const d = getISTDate();
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

const transporter = nodemailer.createTransport({
    host: "smtp.gmail.com",
    port: 465,
    secure: true,
    auth: {
        user: "bluestonesoftwaredeveloper@gmail.com",
        pass: "hwmo dxbr oiub mpfr", // Note: Ensure this App Password corresponds to bluestonesoftwaredeveloper@gmail.com
    },
    tls: { rejectUnauthorized: false },
});

function getLeadCodePrefix(domain = '') {
    const key = String(domain || '').trim().toLowerCase();
    // Prefix mapping based on user request & business logic
    if (key.includes('overseas')) return 'BOC';
    if (key.includes('ias academy') || key === 'ias') return 'BIAS';
    if (key.includes('techpark') || key.includes('tech park') || key === 'tech') return 'BT';
    if (key.includes('placement')) return 'BP';
    if (key.includes('sport')) return 'BES';
    if (key.includes('language')) return 'BLH';
    if (key.includes('preschool') || key.includes('pre school')) return 'BIPS';
    if (key.includes('startup') || key.includes('start-up')) return 'BSP';
    if (key.includes('group') || key.includes('institution')) return 'BGOI';
    if (key.includes('college contact')) return 'BCC';
    return 'BGOI';
}


function normalizeContactDomain(focus = '') {
    const key = String(focus || '').toLowerCase();
    if (key.includes('overseas')) return 'Overseas';
    if (key.includes('ias')) return 'IAS Academy';
    if (key.includes('tech')) return 'Techpark';
    if (key.includes('placement')) return 'Placements';
    if (key.includes('sport')) return 'Elite Sports';
    if (key.includes('language')) return 'Language Hub';
    if (key.includes('preschool')) return 'Preschool';
    if (key.includes('startup') || key.includes('business')) return 'Startup';
    if (key.includes('group')) return 'Group of Institutions';
    if (key.includes('college contact')) return 'College Contacts';
    return focus;
}
function parseFocusList(raw = '') {
    if (Array.isArray(raw)) return raw.map((v) => String(v).trim()).filter(Boolean);
    return String(raw || '')
        .split(',')
        .map((v) => v.trim())
        .filter(Boolean);
}
function getDomainAliases(domainInput = '') {
    // Convert input into an array of clean strings whether sent as "DomainA, DomainB" or ["DomainA", "DomainB"]
    const domainArray = Array.isArray(domainInput) ? domainInput : String(domainInput || '').split(',');
    const aliases = new Set();
    domainArray.forEach(d => {
        const key = String(d || '').trim().toLowerCase().replace(/\s+/g, ' ');
        if (!key) return;
        // 🔥 OVERSEAS
        if (key.includes('overseas')) {
            aliases.add('Overseas');
            aliases.add('Overseas Consulting');
            aliases.add('Overseas Enrollment');
            aliases.add('Bluestone Overseas');
        }
        // 🔥 IAS
        if (key.includes('ias')) {
            aliases.add('IAS Academy');
            aliases.add('IAS');
            aliases.add('Bluestone IAS Academy');
        }
        // 🔥 TECH
        if (key.includes('tech')) {
            aliases.add('Techpark');
            aliases.add('Tech Park');
            aliases.add('Bluestone Techpark');
        }
        // 🔥 PLACEMENT
        if (key.includes('placement')) {
            aliases.add('Placements');
            aliases.add('Placement Services');
            aliases.add('Placement Service');
            aliases.add('Bluestone Placements');
        }
        // 🔥 SPORTS
        if (key.includes('sport')) {
            aliases.add('Elite Sports');
            aliases.add('Sports');
            aliases.add('Bluestone Elite Sports');
        }
        // 🔥 LANGUAGE
        if (key.includes('language') || key.includes('languages')) {
            aliases.add('Language Hub');
            aliases.add('Bluestone Language Hub');
        }
        // 🔥 PRESCHOOL
        if (key.includes('preschool')) {
            aliases.add('Preschool');
            aliases.add('International Pre School');
            aliases.add('International Preschool');
            aliases.add('Bluestone Preschool');
        }
        // 🔥 STARTUP
        if (key.includes('startup') || key.includes('start up') || key.includes('start-up') || key.includes('business')) {
            aliases.add('Startup');
            aliases.add('Start-up');
            aliases.add('Business Ideas');
        }
        // 🔥 GROUP
        if (key.includes('group') || key.includes('institution')) {
            aliases.add('Group of Institutions');
            aliases.add('Bluestone Group');
        }
        // 🔥 COLLEGE CONTACTS
        if (key.includes('college contact') || key.includes('college contacts')) {
            aliases.add('College Contacts');
        }
        // fallback if this specific item didn't match any triggers
        let matched = false;
        if (key.includes('overseas')) matched = true;
        if (key.includes('ias')) matched = true;
        if (key.includes('tech')) matched = true;
        if (key.includes('placement')) matched = true;
        if (key.includes('sport')) matched = true;
        if (key.includes('language')) matched = true;
        if (key.includes('preschool')) matched = true;
        if (key.includes('startup') || key.includes('business')) matched = true;
        if (key.includes('group') || key.includes('institution')) matched = true;
        if (key.includes('college contact')) matched = true;

        if (!matched) {
            aliases.add(d);
        }
    });
    return Array.from(aliases);
}
function appendDomainCondition(sql, params, domainValue, tableAlias = '') {
    const aliases = getDomainAliases(domainValue);
    if (aliases.length === 0) return sql;
    const col = tableAlias ? `${tableAlias}.domain` : 'domain';

    // Use LIKE matching for ALL aliases to support comma-separated domain strings in the DB
    const likeClauses = aliases.map(() => `${col} LIKE ?`).join(' OR ');
    aliases.forEach(alias => params.push(`%${alias}%`));

    return `${sql} AND (${likeClauses})`;
}
async function getNextLeadCode(domain, db = pool) {
    const prefix = getLeadCodePrefix(domain);
    const [rows] = await db.execute(
        `SELECT lead_code
         FROM leads
         WHERE lead_code LIKE ?
         ORDER BY CAST(SUBSTRING_INDEX(lead_code, '-', -1) AS UNSIGNED) DESC
         LIMIT 1`,
        [`${prefix}-%`]
    );
    const last = rows[0]?.lead_code || '';
    const lastNum = parseInt(String(last).split('-')[1], 10);
    const nextNum = Number.isNaN(lastNum) ? 1 : lastNum + 1;
    return `${prefix}-${String(nextNum).padStart(4, '0')}`;
}
function createMailTransporter() {
    const host = SMTP_CONFIG.host;
    const port = Number(SMTP_CONFIG.port || 587);
    const user = SMTP_CONFIG.user;
    const pass = SMTP_CONFIG.pass;
    if (!host || !user || !pass) return null;
    return nodemailer.createTransport({
        host,
        port,
        secure: SMTP_CONFIG.secure ?? (port === 465),
        auth: { user, pass }
    });
}
const sendContactEmail = async (toEmail, subject, htmlContent) => {
    const mailTransporter = createMailTransporter();
    if (!mailTransporter) return;
    try {
        await mailTransporter.sendMail({
            from: SMTP_CONFIG.from || SMTP_CONFIG.user,
            to: toEmail,
            subject: subject,
            html: htmlContent
        });
    } catch (err) {
        console.error(`Failed to send email to ${toEmail}:`, err.message);
    }
};
const triggerEmails = async (candidateName, candidateEmail, candidatePhone, candidateMessage, leadDomain, leadInterest, source = 'Website') => {
    // 1. Send to Candidate
    const candidateSubject = `Thank you for your enquiry - Bluestone Group of Institutions`;
    const candidateHtml = `
        <div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
            <h2>Hello ${candidateName},</h2>
            <p>Thank you for reaching out to us regarding <strong>${leadInterest || leadDomain}</strong>.</p>
            <p>We have received your enquiry and our team will get back to you shortly.</p>
            <br/>
            <p>Best Regards,</p>
            <p><strong>Bluestone Group of Institutions</strong></p>
        </div>
    `;
    sendContactEmail(candidateEmail, candidateSubject, candidateHtml);
    // Fetch Admins & Super Admins
    try {
        const [users] = await pool.execute('SELECT email, role, domain, is_active FROM users WHERE is_active = 1');
        const adminEmails = [];
        const superAdminEmails = [];
        for (const u of users) {
            const tier = getUserTier(u);
            if (tier === 'SUPER_ADMIN' && u.email) {
                superAdminEmails.push(u.email);
            } else if (tier === 'ADMIN' && u.email) {
                // Restrict Agent Admin and Franchise from receiving these emails
                if (u.role === 'Agent Admin' || u.role === 'Franchise') {
                    continue;
                }

                const aliases = getDomainAliases(u.domain);
                if (aliases.includes(leadDomain)) {
                    adminEmails.push(u.email);
                }
            }
        }
        const adminSubject = `New Enquiry Received (${source}): ${leadDomain} - ${candidateName}`;
        const adminHtml = `
            <div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
                <h2>New Lead Enquiry</h2>
                <p>A new enquiry has been submitted from <strong>${source}</strong> for <strong>${leadDomain}</strong>.</p>
                <table style="border-collapse: collapse; width: 100%; max-width: 600px; margin-top: 20px;">
                    <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${candidateName}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${candidateEmail}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Phone:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${candidatePhone}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Interested In:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadInterest || '-'}</td></tr>
                    <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Message:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${candidateMessage || '-'}</td></tr>
                </table>
                <br/>
                <p>Please log in to the portal to view full details.</p>
            </div>
        `;
        if (adminEmails.length > 0) sendContactEmail(adminEmails.join(','), adminSubject, adminHtml);
        if (superAdminEmails.length > 0) sendContactEmail(superAdminEmails.join(','), adminSubject, adminHtml);
    } catch (e) {
        console.error("Error fetching admins for email notification:", e);
    }
};

function formatWho(user = {}) {
    const id = user?.id ? `(${user.id}) ` : '';
    return `${id}${user?.name || 'System'}`.trim();
}
function deriveTierFromRole(role = '') {
    const r = String(role || '').trim();
    if (['Main Admin', 'MD', 'GM', 'Super Admin'].includes(r)) return 'SUPER_ADMIN';
    if (['TL', 'Coordinator', 'Head', 'Admin', 'Agent Admin', 'Franchise'].includes(r)) return 'ADMIN';
    if (['Staff', 'Agent Staff'].includes(r)) return 'STAFF';
    return 'STAFF';
}
function getUserTier(user = {}) {
    return user?.tier || deriveTierFromRole(user?.role);
}
function isSuperAdminUser(user = {}) {
    return getUserTier(user) === 'SUPER_ADMIN';
}
function isAdminUser(user = {}) {
    const t = getUserTier(user);
    return t === 'ADMIN' || t === 'SUPER_ADMIN';
}
function isStaffUser(user = {}) {
    return getUserTier(user) === 'STAFF';
}
function canUserAccessLead(lead, user) {
    if (!lead || !user) return false;

    // Core Super Admin: Unlimited Access
    if (isSuperAdminUser(user)) return true;

    // Agent / Franchise Isolation
    if (user.user_type === 'AGENT' || user.user_type === 'FRANCHISE') {
        if (!user.company_name) return false; // Safety check
        if (lead.company_name !== user.company_name) return false;

        // If they match company, then check role/tier within company
        if (getUserTier(user) === 'ADMIN') return true; // Agent Admin can see all company leads
        return Number(lead.assigned_to) === Number(user.id); // Agent Staff only own
    }

    // Core Admin/Staff Logic
    if (getUserTier(user) === 'ADMIN') {
        const aliases = getDomainAliases(user.domain);
        return aliases.includes(lead.domain);
    }
    if (isStaffUser(user)) {
        if ((user.role === 'Document Handler' || user.designation === 'Document Handler') &&
            (lead.domain === 'Bluestone Overseas' || lead.domain === 'Overseas Enrollment' || lead.domain === 'Overseas')) {
            return true;
        }
        return Number(lead.assigned_to) === Number(user.id);
    }

    return false;
}

let lastGlobalCleanupTime = 0;
const userActivityCache = new Map();

// --- 2. AUTH TRACKING HELPER ---
async function trackUserActivity(userId) {
    if (!userId) return;
    try {
        const nowMs = Date.now();
        const lastUpdate = userActivityCache.get(userId) || 0;

        // 🔥 Interaction-Based Throttle: Only update DB once every 60 seconds
        if (nowMs - lastUpdate < 60000) {
            return;
        }

        userActivityCache.set(userId, nowMs);
        const today = getISTDay();
        const currentHour = getISTDate().getHours();

        // 1. Log hourly activity and 2. update real-time status in parallel
        await Promise.all([
            pool.execute(`
                INSERT INTO user_activity_log (user_id, activity_date, activity_hour, active_minutes, last_request_at)
                VALUES (?, ?, ?, 1, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE 
                active_minutes = CASE 
                    WHEN TIMESTAMPDIFF(MINUTE, last_request_at, CURRENT_TIMESTAMP) > 0 
                    AND TIMESTAMPDIFF(MINUTE, last_request_at, CURRENT_TIMESTAMP) < 10 
                    THEN active_minutes + 1 
                    ELSE active_minutes 
                END,
                last_request_at = CURRENT_TIMESTAMP
            `, [userId, today, currentHour]),
            pool.execute('UPDATE users SET last_active_at = CURRENT_TIMESTAMP, iactive = 1 WHERE id = ?', [userId])
        ]);

    } catch (err) {
        if (!String(err.message).includes('max_connections_per_hour')) {
            console.error(`[Activity Tracking] Error:`, err.message);
        }
    }
}

// --- AUTH MIDDLEWARE (FIXED) ---
const authenticateToken = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    // Fallback to req.body.token for navigator.sendBeacon compatibility
    const token = (authHeader && authHeader.split(' ')[1]) || req.body?.token;

    if (!token) {
        return res.status(401).json({ msg: "Access Denied: No Token Provided" });
    }
    jwt.verify(token, JWT_SECRET, (err, decodedUser) => {
        if (err) {
            return res.status(403).json({ msg: "Invalid or Expired Token" });
        }

        // 🔒 Real-time global edit & delete restriction blocker
        const writeMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        const whitelistPaths = [
            '/api/auth/ping',
            '/api/user/heartbeat',
            '/api/user/offline',
            '/api/auth/verify-password',
            '/api/leads/bulk',
            '/api/leads'
        ];

        // If performing any editing or deletion action
        if (writeMethods.includes(req.method) && !whitelistPaths.includes(req.path)) {
            pool.execute(
                'SELECT can_edit FROM users WHERE id = ? AND deleted_at IS NULL',
                [decodedUser.id]
            ).then(([rows]) => {
                const userInDb = rows[0];
                if (userInDb && userInDb.can_edit === 0) {
                    return res.status(403).json({ msg: "Security Restriction: You do not have permission to edit or delete data in this CRM." });
                }
                req.user = decodedUser;
                next();
            }).catch(dbErr => {
                console.error("Edit permission check error:", dbErr);
                // Fallback to JWT payload if DB lookup fails
                if (decodedUser.can_edit === 0) {
                    return res.status(403).json({ msg: "Security Restriction: You do not have permission to edit or delete data in this CRM." });
                }
                req.user = decodedUser;
                next();
            });
        } else {
            req.user = decodedUser;
            next();
        }
    });
};
// --- 3. INITIALIZE TABLES ---
const initDB = async () => {
    let connection;
    try {
        connection = await pool.getConnection();
        // Set Session Timezone to IST (+05:30)
        await connection.query("SET time_zone = '+05:30'");

        // Hostinger optimization: Table verification is retired to save on hourly connection limits.
        // The core schema is already established and stable.

        // ❌ ENSURE SYSTEM EMAILS TABLE EXISTS
        await connection.query(`
            CREATE TABLE IF NOT EXISTS system_emails (
                id INT AUTO_INCREMENT PRIMARY KEY,
                label VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                is_active TINYINT DEFAULT 1,
                created_at DATETIME
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        `);

        // ❌ MIGRATE DYNAMIC CATEGORIES FOR COUNTRY ISOLATION
        try {
            await connection.query("ALTER TABLE learning_categories ADD COLUMN country_code VARCHAR(50) NULL");
        } catch (e) {
            // Already added
        }
        try {
            await connection.query("ALTER TABLE learning_categories DROP INDEX code");
        } catch (e) {
            // Already dropped
        }

        // 🖼️ CANDIDATE PHOTO: Ensure column exists
        try {
            await connection.query("ALTER TABLE leads ADD COLUMN candidate_photo VARCHAR(512) NULL");
        } catch (e) { /* Column already exists */ }

        const [statusColumns] = await connection.query("SHOW COLUMNS FROM leads LIKE 'status'");
        if (statusColumns[0]?.Type?.toLowerCase().startsWith('enum(')) {
            const nullability = statusColumns[0].Null === 'NO' ? 'NOT NULL' : 'NULL';
            const defaultValue = statusColumns[0].Default;
            const defaultClause = defaultValue === null
                ? 'DEFAULT NULL'
                : `DEFAULT '${String(defaultValue).replace(/'/g, "''")}'`;
            await connection.query(
                `ALTER TABLE leads MODIFY COLUMN status VARCHAR(100) ${nullability} ${defaultClause}`
            );
            console.log('Migrated leads.status from ENUM to VARCHAR');
        }

        const [historyTables] = await connection.query("SHOW TABLES LIKE 'lead_edit_history'");
        if (historyTables.length > 0) {
            const [repairResult] = await connection.query(`
                UPDATE leads l
                JOIN lead_edit_history latest
                    ON latest.lead_id = l.id
                    AND latest.action_type = 'STATUS_UPDATE'
                    AND latest.field_name = 'status'
                LEFT JOIN lead_edit_history newer
                    ON newer.lead_id = latest.lead_id
                    AND newer.action_type = 'STATUS_UPDATE'
                    AND newer.field_name = 'status'
                    AND (
                        newer.changed_at > latest.changed_at
                        OR (newer.changed_at = latest.changed_at AND newer.id > latest.id)
                    )
                SET l.status = latest.new_value
                WHERE (l.status IS NULL OR TRIM(l.status) = '')
                    AND newer.id IS NULL
                    AND latest.new_value IN (
                        'New', 'Follow Up', 'Waiting for Confirmation', 'Enrolled',
                        'Closed', 'Invalid', 'Dropped', 'Contacted', 'Confirmed',
                        'Interested', 'Not Interested'
                    )
            `);
            if (repairResult.affectedRows > 0) {
                console.log(`Restored ${repairResult.affectedRows} blank lead statuses from edit history`);
            }
        }

        // 🚀 PERFORMANCE INDEXES: Ensure critical query columns are indexed
        const indexes = [
            "CREATE INDEX idx_leads_domain ON leads (domain(100))",
            "CREATE INDEX idx_leads_status ON leads (status)",
            "CREATE INDEX idx_leads_is_active ON leads (is_active)",
            "CREATE INDEX idx_leads_assigned_to ON leads (assigned_to)",
            "CREATE INDEX idx_leads_created_at ON leads (created_at)",
            "CREATE INDEX idx_leads_updated_at ON leads (updated_at)",
            "CREATE INDEX idx_leads_phone ON leads (phone)",
            "CREATE INDEX idx_leads_domain_status ON leads (domain(80), status, is_active)",
            "CREATE INDEX idx_remarks_lead_id_created ON lead_remarks_messages (lead_id, created_at DESC)"
        ];
        for (const idxSql of indexes) {
            try { await connection.query(idxSql); } catch (e) { /* Index already exists */ }
        }

        console.log("✅ Database Connectivity & Core Schema Verified");
    } catch (err) {
        console.error("❌ Database Connection Error:", err.message);
    } finally {
        if (connection) connection.release();
    }
};

// Global error handler for body parser limits to return a friendly JSON response
app.use((err, req, res, next) => {
    if (err && err.type === 'entity.too.large') {
        res.status(413).json({ error: 'Payload Too Large' });
    } else {
        next(err);
    }
});
// --- 4. ROUTES ---
// Public Contact Enquiry (website form -> portal leads)
app.post('/api/contact', async (req, res) => {
    try {
        const { name, email, phone, message, businessFocus, domain, category, interested_in, passport_number, portal_signup } = req.body;

        if (!name || !phone || !email) {
            return res.status(400).json({ error: "Missing required fields" });
        }

        if (portal_signup === true) {
            const phoneDigits = String(phone).replace(/\D/g, '');
            const nationalDigits = String(req.body.phone_national || phoneDigits).replace(/\D/g, '');
            const countryDigits = String(req.body.phone_country_code || '').replace(/\D/g, '');
            const phoneCandidates = [...new Set([
                phoneDigits,
                nationalDigits,
                countryDigits + nationalDigits,
                '00' + countryDigits + nationalDigits
            ].filter(Boolean))];
            const placeholders = phoneCandidates.map(() => '?').join(', ');
            const [existingLeads] = await pool.execute(
                `SELECT id FROM leads
                 WHERE REGEXP_REPLACE(COALESCE(phone, ''), '[^0-9]', '') IN (${placeholders})
                 ORDER BY id DESC
                 LIMIT 1`,
                phoneCandidates
            );
            if (existingLeads.length > 0) {
                return res.json({
                    success: true,
                    duplicate: true,
                    message: 'An existing CRM record uses this mobile number. No duplicate lead was created.'
                });
            }
        }
        // Backward compatible mode: old businessFocus payload
        if (!businessFocus?.length) {
            return res.status(400).json({ error: "Missing required fields" });
        }
        const normalizedDomains = Array.from(
            new Set(
                (Array.isArray(businessFocus) ? businessFocus : [businessFocus]).map((raw) =>
                    normalizeContactDomain(raw)
                )
            )
        );
        for (const domainName of normalizedDomains) {
            const leadCode = await getNextLeadCode(domainName);
            await pool.execute(
                `INSERT INTO leads
                 (student_name, passport_number, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New', 1, ?)`,
                [
                    name,
                    passport_number || null,
                    email,
                    phone,
                    domainName,
                    'Website Contact',
                    'Website Enquiry',
                    domainName,
                    message || '',
                    leadCode,
                    nowStr
                ]
            );
            triggerEmails(name, email, phone, message, domainName, domainName, 'Website Contact');
        }
        res.json({ success: true, message: "Enquiry saved successfully" });
    } catch (err) {
        console.error("CONTACT API ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});


// Mobile App Enquiry (Flutter app -> portal leads + BGOI Sync)
app.post('/api/enquiry', async (req, res) => {
    try {
        const { user_id, enquiry_for, candidate_name, service_type, details } = req.body;
        const userId = Number(user_id || req.body.app_user_id || req.body.userId) || null;

        const name = candidate_name || details?.full_name || 'Mobile User';
        const email = details?.email || '';
        const phone = details?.phone || '';
        const message = details?.message || details?.remarks || '';
        const service = service_type || enquiry_for || 'General';
        const normalizedDomain = normalizeContactDomain(service);
        const leadCode = await getNextLeadCode(normalizedDomain);

        const nowStr = getISTString();
        // 1. Insert into BGOI Specific table (Required for your app records)
        await pool.execute(
            `INSERT INTO bgoi_enquiries 
             (user_id, lead_code, enquiry_for, candidate_name, full_name, service_type, phone, email, remarks, budget, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [userId, leadCode, enquiry_for || '', candidate_name || '', name, service, phone, email, message, details?.budget || '', nowStr]
        );

        // 2. Insert into Main leads table (Required for CRM Dashboard)
        await pool.execute(
            `INSERT INTO leads
             (student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, app_user_id, created_at)
             VALUES (?, ?, ?, ?, 'Mobile App', 'Mobile Enquiry', ?, ?, ?, 'New', 1, ?, ?)`,
            [name, email, phone, normalizedDomain, service, message, leadCode, userId, nowStr]
        );

        // 3. Trigger BGOI App Notification
        if (userId) {
            await pool.execute(
                "INSERT INTO bgoi_notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, 'Enquiry', ?)",
                [userId, "Enquiry Submitted!", `Ref: ${leadCode}`, nowStr]
            );
        }

        triggerEmails(name, email, phone, message, normalizedDomain, service, 'Mobile App');
        res.status(201).json({ success: true, message: "Enquiry submitted successfully", lead_code: leadCode });
    } catch (err) {
        console.error("MOBILE ENQUIRY API ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});


// Login
app.post('/api/auth/login', async (req, res) => {
    const { email, password } = req.body;
    try {
        const [rows] = await pool.execute('SELECT * FROM users WHERE email = ? AND is_active = 1', [email]);
        if (rows.length === 0) return res.status(400).json({ msg: "Invalid credentials" });

        const user = rows[0];
        const [tierRows] = await pool.execute(
            'SELECT tier FROM master_user_hierarchy WHERE role_name = ? AND is_active = 1 LIMIT 1',
            [user.role]
        );
        const tier = tierRows[0]?.tier || deriveTierFromRole(user.role);
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) return res.status(400).json({ msg: "Invalid credentials" });
        const token = jwt.sign(
            {
                id: user.id,
                employee_id: user.employee_id || null,
                name: user.name,
                role: user.role,
                designation: user.designation,
                domain: user.domain,
                tier: user.tier || tier,
                user_type: user.user_type || 'CORE',
                company_name: user.company_name,
                can_export_copy: user.can_export_copy || 0,
                can_edit: user.can_edit !== undefined ? user.can_edit : 1,
                show_country_menu: user.show_country_menu || 0
            },
            JWT_SECRET,
            { expiresIn: '12h' }
        );
        res.json({
            token,
            user: {
                id: user.id,
                employee_id: user.employee_id || null,
                name: user.name,
                role: user.role,
                domain: user.domain,
                designation: user.designation || null,
                tier: user.tier || tier,
                user_type: user.user_type || 'CORE',
                company_name: user.company_name,
                can_export_copy: user.can_export_copy || 0,
                can_edit: user.can_edit !== undefined ? user.can_edit : 1,
                show_country_menu: user.show_country_menu || 0
            }
        });

        // 🚀 Mark user as ONLINE immediately
        trackUserActivity(user.id);

        // --- IP, Geolocation, and Notification Logic ---
        let userIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress || req.ip || "Unknown IP";
        if (userIp && userIp.includes(',')) {
            userIp = userIp.split(',')[0].trim();
        }

        let location = req.body.location || "Unknown Location"; // Extracted from Frontend browser geolocation

        // Save to DB
        try {
            await pool.execute(
                'INSERT INTO login_history (user_id, ip_address, location) VALUES (?, ?, ?)',
                [user.id, userIp, location]
            );
        } catch (dbErr) {
            console.error("DB insert for login history failed:", dbErr);
        }

        // Send Email Alert
        try {
            const mailOptions = {
                from: '"Bluestone 360 Security Alert" <info@bluestoneocs.com>',
                to: 'bluestoneocs@gmail.com, admin@bluestoneocs.com, bluestonesoftwaredeveloper@gmail.com',
                subject: `Security Alert: Bluestone 360 Login by ${user.name}`,
                html: `
                    <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                        <div style="background-color: #003366; color: #fff; padding: 20px; text-align: center;">
                            <h2>Bluestone 360</h2>
                        </div>
                        <div style="padding: 20px;">
                            <p><strong>A user has just logged into the Bluestone 360.</strong></p>
                            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Name</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${user.name}</td></tr>
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Email</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${user.email}</td></tr>
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Role</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${user.role}</td></tr>
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Login Time</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })} (IST)</td></tr>
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>IP Address</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${userIp}</td></tr>
                                <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Browser Region</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${location}</td></tr>
                            </table>
                        </div>
                        <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #777;">
                            <p>This is an automated security message from Bluestone Group of Institutions.</p>
                        </div>
                    </div>
                `
            };
            transporter.sendMail(mailOptions).catch(err => console.error("Login email send error:", err));
        } catch (mailErr) {
            console.error("Login email config error:", mailErr);
        }

    } catch (err) {
        res.status(500).json({ error: "Login failed" });
    }
});

// Get current user
app.get('/api/auth/me', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute(
            'SELECT id, name, email, role, domain, designation, tier, user_type, company_name, can_export_copy, can_edit, show_country_menu FROM users WHERE id = ?',
            [req.user.id]
        );
        if (rows.length === 0) return res.status(404).json({ msg: "User not found" });
        res.json(rows[0]);
    } catch (err) {
        res.status(500).json({ error: "Failed to fetch user" });
    }
});

// --- Activity Ping ---
// Throttled endpoint that frontend calls to keep last_active_at updated without making data requests
app.post('/api/auth/ping', authenticateToken, (req, res) => {
    // Explicitly track activity here (moved from authenticateToken middleware)
    if (typeof trackUserActivity === "function") {
        trackUserActivity(req.user.id);
    }
    res.json({ success: true, msg: "Activity recorded" });
});

// Alias for auth/ping used by the new global heartbeat tracking
app.post('/api/user/heartbeat', authenticateToken, (req, res) => {
    if (typeof trackUserActivity === "function") {
        trackUserActivity(req.user.id);
    }
    res.json({ success: true });
});

// Offline Beacon (used on tab close/logout)
app.post('/api/user/offline', authenticateToken, async (req, res) => {
    try {
        await pool.execute('UPDATE users SET iactive = 0 WHERE id = ?', [req.user.id]);
        res.json({ success: true });
    } catch (err) {
        res.status(500).json({ error: "Offline update failed" });
    }
});
// --- NEW: GRANULAR DASHBOARD STATS ---
app.get('/api/dashboard/stats/:timeframe/:type', authenticateToken, async (req, res) => {
    const { timeframe, type } = req.params;
    let { domain, userId: filterUserId } = req.query;
    const { role, domain: userDomain, id: loggedUserId } = req.user;

    // Security: Only Super Admins can query "All" or other domains
    const isSuperAdmin = isSuperAdminUser(req.user);
    if (!isSuperAdmin) domain = userDomain;

    try {
        const isSuperAdmin = isSuperAdminUser(req.user);
        if (!isSuperAdmin) domain = userDomain;

        let staffFilter = '';
        let staffParams = [];

        // 0. Company Isolation
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            staffFilter += ' AND company_name = ?';
            staffParams.push(req.user.company_name);
        }

        if (filterUserId && filterUserId !== 'All') {
            staffFilter += " AND assigned_to = ?";
            staffParams.push(filterUserId);
        } else if (isStaffUser(req.user)) {
            staffFilter += " AND assigned_to = ?";
            staffParams.push(loggedUserId);
        }

        // --- 1. LEADS TABLE PART ---
        let leadsSql = `SELECT status, domain, created_at FROM leads WHERE is_active = 1 ${staffFilter}`;
        let leadsParams = [...staffParams];

        // --- 2. MOBILE ENQUIRIES PART (Only if status matches 'New' or counting all enquiries) ---
        let mobileSql = "";
        let mobileParams = [];

        // Mobile entries only exist for 'enquiry' (all) or 'New' (special case)
        // If we are looking for 'admission' etc, mobile app doesn't have them yet (they move to leads)
        if (type === 'enquiry') {
            mobileSql = `
                SELECT 'New' AS status, service_type AS domain, created_at 
                FROM bgoi_enquiries e 
                WHERE 1=1 
                AND NOT EXISTS (
                    SELECT 1 FROM leads l 
                    WHERE l.source = 'Mobile App' AND l.student_name = e.candidate_name AND l.created_at = e.created_at
                )
            `;
            // NOTE: Mobile app enquiries have NO assigned_to yet. 
            // So if specifically filtering for a Staff user, they are naturally 0.
            if (staffFilter) mobileSql = ""; // Exclude unassigned mobile enquiries if a staff is selected
        }

        // Combine into a final counting query
        let finalSql = "";
        let finalParams = [];

        if (mobileSql) {
            finalSql = `SELECT COUNT(*) as count FROM ((${leadsSql}) UNION ALL (${mobileSql})) as combined WHERE 1=1`;
            finalParams = [...leadsParams, ...mobileParams];
        } else {
            finalSql = `SELECT COUNT(*) as count FROM (${leadsSql}) as combined WHERE 1=1`;
            finalParams = [...leadsParams];
        }

        // Apply Status Filter
        if (type === 'followup') finalSql += " AND status = 'Follow Up'";
        else if (type === 'enrolled') finalSql += " AND status = 'Enrolled'";
        else if (type === 'pending') finalSql += " AND status = 'Waiting for Confirmation'";
        else if (type === 'invalid') finalSql += " AND status = 'Invalid'";

        // Apply Domain Filter (Using appendDomainCondition on the combined result)
        if (domain && domain !== 'All') {
            finalSql = appendDomainCondition(finalSql, finalParams, domain);
        } else if (!isSuperAdmin && userDomain) {
            finalSql = appendDomainCondition(finalSql, finalParams, userDomain);
        }

        // Apply Timeframe Filter
        if (timeframe === 'today' || timeframe === 'history') {
            const { date } = req.query;
            if (date) {
                finalSql += " AND DATE(created_at) = ?";
                finalParams.push(date);
            } else if (timeframe === 'today') {
                finalSql += " AND DATE(created_at) = CURDATE()";
            }
        } else if (timeframe === 'week') {
            finalSql += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        } else if (timeframe === 'month') {
            finalSql += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        } else if (timeframe === 'year') {
            finalSql += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)";
        } else if (timeframe === 'custom') {
            const { startDate, endDate } = req.query;
            if (startDate && endDate) {
                finalSql += " AND DATE(created_at) BETWEEN ? AND ?";
                finalParams.push(startDate, endDate);
            }
        }

        const [rows] = await pool.execute(finalSql, finalParams);
        res.json({ count: rows[0].count });
    } catch (err) {
        console.error("Stats API Error:", err);
        res.status(500).json({ count: 0, error: err.message });
    }
});

// --- NEW: BULK DASHBOARD STATS (Consolidates 10 requests into 1) ---
app.get('/api/dashboard/stats-bulk', authenticateToken, async (req, res) => {
    let { domain, userId, timeframe, date, startDate, endDate, source_type, company_name: qCompanyName } = req.query;
    const { domain: userDomain } = req.user;
    const isSuperAdmin = isSuperAdminUser(req.user);
    if (!isSuperAdmin) {
        if (domain && domain !== 'All') {
            // Validate selected domain is in user's authorized list
            const authorized = String(userDomain || '').split(',').map(d => d.trim().toLowerCase());
            if (!authorized.includes(domain.toLowerCase().trim())) {
                domain = userDomain; // Fallback to full unauthorized list or primary domain
            }
        } else {
            domain = userDomain;
        }
    }

    // --- SINGLE-QUERY AGGREGATION (replaces 10 parallel queries) ---
    // Builds WHERE clause for base filters (domain, company, staff)
    const buildBaseWhere = () => {
        let where = 'is_active = 1';
        let params = [];

        // Department/Domain Filter
        if (domain && domain !== 'All') {
            const aliases = getDomainAliases(domain);
            if (aliases.length > 0) {
                const likeClauses = aliases.map(() => 'domain LIKE ?').join(' OR ');
                aliases.forEach(a => params.push(`%${a}%`));
                where += ` AND (${likeClauses})`;
            }
        }

        // Company Filter (Isolation)
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            where += ' AND company_name = ?';
            params.push(req.user.company_name);
        }

        // Staff/User Filter
        if (isStaffUser(req.user)) {
            where += ' AND assigned_to = ?';
            params.push(req.user.id);
        } else if (userId && userId !== 'All') {
            where += ' AND assigned_to = ?';
            params.push(Number(userId));
        }

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                where += " AND (source_type = 'Agent' OR (company_name IS NOT NULL AND company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                where += " AND (source_type = 'Own' OR source_type IS NULL OR source_type = '') AND (company_name IS NULL OR company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                where += ' AND source_type = ?';
                params.push(source_type);
            }
        }

        if (qCompanyName && qCompanyName !== 'All') {
            where += ' AND company_name = ?';
            params.push(qCompanyName);
        }

        return { where, params };
    };

    // Builds the period date fragment
    const buildPeriodFragment = () => {
        if (timeframe === 'today' || (timeframe === 'history' && date)) {
            return { fragment: 'AND DATE(created_at) = ?', periodParams: [date || getISTDay()] };
        } else if (timeframe === 'week') {
            return { fragment: 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)', periodParams: [] };
        } else if (timeframe === 'month') {
            return { fragment: 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)', periodParams: [] };
        } else if (timeframe === 'year') {
            return { fragment: 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)', periodParams: [] };
        } else if (timeframe === 'custom' && startDate && endDate) {
            return { fragment: 'AND DATE(created_at) BETWEEN ? AND ?', periodParams: [startDate, endDate] };
        }
        return { fragment: 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)', periodParams: [] };
    };

    try {
        const { where, params: baseParams } = buildBaseWhere();
        const { fragment: periodFrag, periodParams } = buildPeriodFragment();

        // ✅ SINGLE QUERY: All 10 stats in one DB round-trip using conditional aggregation
        const sql = `
            SELECT
                COUNT(*) AS total_enquiry,
                SUM(CASE WHEN status = 'Follow Up' THEN 1 ELSE 0 END) AS total_followup,
                SUM(CASE WHEN payment_status IN ('Advance payment','Partial Payment','Full payment','No Fee') THEN 1 ELSE 0 END) AS total_enrolled,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Dropped' THEN 1 ELSE 0 END) AS total_dropped,
                SUM(CASE WHEN status = 'Waiting for Confirmation' THEN 1 ELSE 0 END) AS total_pending,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Invalid' THEN 1 ELSE 0 END) AS total_invalid,
                SUM(CASE WHEN status = 'Enrolled' AND overseas_pipeline_status != 'Document Collection' THEN 1 ELSE 0 END) AS total_processed,
                SUM(CASE WHEN status = 'Enrolled' AND overseas_pipeline_status = 'CAS / I-20 Processing' THEN 1 ELSE 0 END) AS total_cas_team,
                SUM(CASE WHEN status = 'Closed' AND overseas_pipeline_status = 'Completed' THEN 1 ELSE 0 END) AS total_completed,
                SUM(CASE WHEN status = 'Contacted' THEN 1 ELSE 0 END) AS total_contacted,
                SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) AS total_confirmed,
                SUM(CASE WHEN status = 'Interested' THEN 1 ELSE 0 END) AS total_interested,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Not Interested' THEN 1 ELSE 0 END) AS total_not_interested,
                SUM(CASE WHEN status = 'New' THEN 1 ELSE 0 END) AS total_new,
                SUM(CASE WHEN ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_enquiry,
                SUM(CASE WHEN status = 'Follow Up' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_followup,
                SUM(CASE WHEN payment_status IN ('Advance payment','Partial Payment','Full payment','No Fee') AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_enrolled,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Dropped' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_dropped,
                SUM(CASE WHEN status = 'Waiting for Confirmation' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_pending,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Invalid' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_invalid,
                SUM(CASE WHEN status = 'Enrolled' AND overseas_pipeline_status != 'Document Collection' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_processed,
                SUM(CASE WHEN status = 'Enrolled' AND overseas_pipeline_status = 'CAS / I-20 Processing' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_cas_team,
                SUM(CASE WHEN status = 'Closed' AND overseas_pipeline_status = 'Completed' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_completed,
                SUM(CASE WHEN status = 'Contacted' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_contacted,
                SUM(CASE WHEN status = 'Confirmed' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_confirmed,
                SUM(CASE WHEN status = 'Interested' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_interested,
                SUM(CASE WHEN COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) = 'Not Interested' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_not_interested,
                SUM(CASE WHEN status = 'New' AND ${periodFrag.replace('AND ', '')} THEN 1 ELSE 0 END) AS period_new
            FROM leads
            WHERE ${where}
        `;
        // Period params are used multiple times (once per CASE in SELECT), so they must come FIRST
        // The WHERE clause (baseParams) comes at the end of the query.
        const allParams = [
            ...periodParams, // for period_enquiry
            ...periodParams, // for period_followup
            ...periodParams, // for period_enrolled
            ...periodParams, // for period_dropped
            ...periodParams, // for period_pending
            ...periodParams, // for period_invalid
            ...periodParams, // for period_processed
            ...periodParams, // for period_cas_team
            ...periodParams, // for period_completed
            ...periodParams, // for period_contacted
            ...periodParams, // for period_confirmed
            ...periodParams, // for period_interested
            ...periodParams, // for period_not_interested
            ...periodParams, // for period_new
            ...baseParams    // for WHERE clause
        ];

        const rows = await query(sql, allParams);
        const r = rows[0] || {};

        res.json({
            totals: {
                enquiry: Number(r.total_enquiry || 0),
                followup: Number(r.total_followup || 0),
                enrolled: Number(r.total_enrolled || 0),
                dropped: Number(r.total_dropped || 0),
                pending: Number(r.total_pending || 0),
                invalid: Number(r.total_invalid || 0),
                processed: Number(r.total_processed || 0),
                cas_team: Number(r.total_cas_team || 0),
                completed: Number(r.total_completed || 0),
                contacted: Number(r.total_contacted || 0),
                confirmed: Number(r.total_confirmed || 0),
                interested: Number(r.total_interested || 0),
                not_interested: Number(r.total_not_interested || 0),
                new: Number(r.total_new || 0),
            },
            periods: {
                enquiry: Number(r.period_enquiry || 0),
                followup: Number(r.period_followup || 0),
                enrolled: Number(r.period_enrolled || 0),
                dropped: Number(r.period_dropped || 0),
                pending: Number(r.period_pending || 0),
                invalid: Number(r.period_invalid || 0),
                processed: Number(r.period_processed || 0),
                cas_team: Number(r.period_cas_team || 0),
                completed: Number(r.period_completed || 0),
                contacted: Number(r.period_contacted || 0),
                confirmed: Number(r.period_confirmed || 0),
                interested: Number(r.period_interested || 0),
                not_interested: Number(r.period_not_interested || 0),
                new: Number(r.period_new || 0),
            }
        });
    } catch (err) {
        console.error("Bulk Stats Error:", err);
        // Graceful fallback: return zeros so dashboard still loads
        res.status(200).json({
            totals: { enquiry: 0, followup: 0, enrolled: 0, dropped: 0, pending: 0, invalid: 0, processed: 0, cas_team: 0, completed: 0, contacted: 0, confirmed: 0, interested: 0, not_interested: 0, new: 0 },
            periods: { enquiry: 0, followup: 0, enrolled: 0, dropped: 0, pending: 0, invalid: 0, processed: 0, cas_team: 0, completed: 0, contacted: 0, confirmed: 0, interested: 0, not_interested: 0, new: 0 },
            error: 'Stats temporarily unavailable'
        });
    }
});

// --- NEW: DOMAIN-WISE FINANCIAL ANALYTICS FOR SUPER ADMIN ONLY ---
app.get('/api/dashboard/financial-stats', authenticateToken, async (req, res) => {
    try {
        if (!isSuperAdminUser(req.user)) {
            return res.status(403).json({ error: "Access denied. Super Admin only." });
        }

        const { timeframe, date, startDate, endDate } = req.query;

        // Build period filter
        let periodWhere = '';
        let periodParams = [];

        if (timeframe === 'today' || (timeframe === 'history' && date)) {
            periodWhere = 'AND DATE(created_at) = ?';
            periodParams.push(date || getISTDay());
        } else if (timeframe === 'week') {
            periodWhere = 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
        } else if (timeframe === 'month') {
            periodWhere = 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
        } else if (timeframe === 'year') {
            periodWhere = 'AND created_at >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)';
        } else if (timeframe === 'custom' && startDate && endDate) {
            periodWhere = 'AND DATE(created_at) BETWEEN ? AND ?';
            periodParams.push(startDate, endDate);
        }

        // 1. Fetch actual stats from leads table (grouped by short domain name)
        const statsQuery = `
            SELECT
                domain,
                SUM(CASE WHEN status = 'Enrolled' THEN 1 ELSE 0 END) as total_leads,
                SUM(CASE WHEN status = 'Dropped' AND LOWER(payment_status) != 'no refund' THEN 1 ELSE 0 END) as dropped_count,
                SUM(CASE WHEN refund_status IN ('Requested', 'Processing') THEN 1 ELSE 0 END) as refund_pending_count,
                SUM(COALESCE(refund_amount, 0)) as total_refunded_amount,
                SUM(CASE WHEN status = 'Enrolled' AND payment_status = 'Full payment' THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN status = 'Enrolled' AND payment_status IN ('Partial Payment', 'Advance payment') THEN 1 ELSE 0 END) as partial_count,
                SUM(CASE WHEN status = 'Enrolled' AND (payment_status = 'Pending payment' OR payment_status IS NULL OR payment_status = '' OR payment_status = 'Pending') THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'Enrolled' THEN COALESCE(total_fees, 0) ELSE COALESCE(paid_amount, 0) END) as total_fees,
                SUM(COALESCE(paid_amount, 0)) as paid_amount,
                SUM(CASE WHEN status = 'Enrolled' THEN COALESCE(total_fees, 0) - COALESCE(paid_amount, 0) ELSE 0 END) as pending_amount
            FROM leads
            WHERE is_active = 1 AND (deleted_at IS NULL) AND status IN ('Enrolled', 'Dropped') AND (payment_status IN ('Advance payment','Partial Payment','Full payment','No Fee', 'No Refund') OR payment_status LIKE '%refund%')
            ${periodWhere}
            GROUP BY domain
        `;

        const [statRows] = await pool.execute(statsQuery, periodParams);

        // 2. Fetch all domains from master_domains to ensure zero states are shown
        const [domainRows] = await pool.execute('SELECT name FROM master_domains ORDER BY name ASC');

        // 3. Merge results
        const finalData = domainRows.map(md => {
            // Normalize names: "Bluestone IAS Academy" -> "IAS Academy"
            const shortName = md.name.replace(/^(Bluestone|BGOI)\s+/i, '').trim();

            // Find match in stats (checking both full and short name)
            const stats = statRows.find(s =>
                s.domain === shortName ||
                s.domain === md.name ||
                md.name.toLowerCase().includes(s.domain.toLowerCase())
            );

            return {
                domain: shortName, // Return short name for UI consistency
                total_leads: stats ? Number(stats.total_leads) : 0,
                dropped_count: stats ? Number(stats.dropped_count) : 0,
                refund_pending_count: stats ? Number(stats.refund_pending_count) : 0,
                total_refunded_amount: stats ? Number(stats.total_refunded_amount) : 0,
                paid_count: stats ? Number(stats.paid_count) : 0,
                partial_count: stats ? Number(stats.partial_count) : 0,
                pending_count: stats ? Number(stats.pending_count) : 0,
                total_fees: stats ? Number(stats.total_fees) : 0,
                paid_amount: stats ? Number(stats.paid_amount) : 0,
                pending_amount: stats ? Number(stats.pending_amount) : 0,
                net_revenue: stats ? (Number(stats.paid_amount) - Number(stats.total_refunded_amount)) : 0
            };
        });

        res.status(200).json({ success: true, data: finalData });
    } catch (err) {
        console.error("Financial Stats API Error:", err);
        res.status(500).json({ error: "Internal server error" });
    }
});

// --- NEW: FINANCIAL LEADS DETAIL TABLE (Super Admin only) ---
app.get('/api/dashboard/financial-leads', authenticateToken, async (req, res) => {
    try {
        if (!isSuperAdminUser(req.user)) {
            return res.status(403).json({ error: "Super Admin only." });
        }

        const { domain, payment_status, page = 1, limit = 20, timeframe, date, startDate, endDate } = req.query;
        const offset = (Number(page) - 1) * Number(limit);
        const params = [];

        let where = `WHERE l.is_active = 1 AND l.deleted_at IS NULL`;

        if (timeframe === 'today' || (timeframe === 'history' && date)) {
            where += ' AND DATE(l.created_at) = ?';
            params.push(date || getISTDay());
        } else if (timeframe === 'week') {
            where += ' AND l.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
        } else if (timeframe === 'month') {
            where += ' AND l.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
        } else if (timeframe === 'year') {
            where += ' AND l.created_at >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)';
        } else if (timeframe === 'custom' && startDate && endDate) {
            where += ' AND DATE(l.created_at) BETWEEN ? AND ?';
            params.push(startDate, endDate);
        }

        if (domain && domain !== 'All') {
            where = appendDomainCondition(where, params, domain, 'l');
        }

        if (payment_status && payment_status !== 'All') {
            if (payment_status === 'Pending payment') {
                where += ` AND l.status = 'Enrolled' AND (l.payment_status = 'Pending payment' OR l.payment_status IS NULL OR l.payment_status = '' OR l.payment_status = 'Pending')`;
            } else if (payment_status === 'dropped_paid') {
                where += ` AND l.status = 'Dropped' AND (l.payment_status IN ('Advance payment','Partial Payment','Full payment','No Fee') OR (l.payment_status LIKE '%refund%' AND LOWER(l.payment_status) != 'no refund')) AND LOWER(l.payment_status) != 'no refund'`;
            } else if (payment_status === 'refunded') {
                where += ` AND (l.refund_status IN ('Requested', 'Processing', 'Refunded') OR (l.payment_status LIKE '%refund%' AND LOWER(l.payment_status) != 'no refund'))`;
            } else if (payment_status === 'enrolled_leads') {
                where += ` AND l.status = 'Enrolled'`;
            } else if (payment_status === 'collected') {
                where += ` AND l.status = 'Enrolled' AND l.payment_status IN ('Advance payment','Partial Payment','Full payment')`;
            } else if (payment_status === 'pending_dues') {
                where += ` AND l.status = 'Enrolled' AND COALESCE(l.total_fees, 0) > COALESCE(l.paid_amount, 0)`;
            } else {
                where += ` AND l.status = 'Enrolled' AND l.payment_status = ?`;
                params.push(payment_status);
            }
        } else {
            where += ` AND l.status = 'Enrolled'`;
        }

        const countSql = `SELECT COUNT(*) as total FROM leads l ${where}`;
        const [countRows] = await pool.execute(countSql, params);
        const total = countRows[0]?.total || 0;

        const sql = `
            SELECT 
                l.id, l.lead_code, l.student_name, l.phone, l.email,
                l.category, l.interested_in, l.payment_status,
                l.total_fees, l.paid_amount,
                (COALESCE(l.total_fees, 0) - COALESCE(l.paid_amount, 0)) AS pending_amount,
                l.refund_status, l.refund_amount,
                l.status, l.domain, l.assigned_to_name
            FROM leads l
            ${where}
            ORDER BY l.total_fees DESC, l.created_at DESC
            LIMIT ? OFFSET ?
        `;
        params.push(Number(limit), offset);
        const [rows] = await pool.execute(sql, params);

        res.json({ success: true, data: rows, total, page: Number(page), limit: Number(limit) });
    } catch (err) {
        console.error("Financial Leads Error:", err);
        res.status(500).json({ error: "Internal server error" });
    }
});

// --- NEW: TODAY & PENDING LEADS LIST ---
app.get('/api/dashboard/leads-filter', authenticateToken, async (req, res) => {
    const { role, domain: userDomain, id: loggedUserId } = req.user;
    const {
        filterType,
        domain: selectedDomain,
        userId: filterUserId,
        timeframe = 'today',
        date,
        startDate,
        endDate,
        source_type,
        company_name
    } = req.query;

    const isSuperAdmin = isSuperAdminUser(req.user);

    try {
        // Standardized Base Query to match Stats Cards - select updated_at as well
        const combinedBase = `
            (
                SELECT id, student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, payment_status, total_fees, paid_amount, invalid_reason, is_active, deleted_at, deleted_by, status, primary_status, assigned_to, assigned_to_name, assigned_by, assigned_by_name, source_type, company_name, created_at, updated_at
                FROM leads
                WHERE is_active = 1
                UNION ALL
                SELECT e.id, candidate_name AS student_name, e.email, e.phone, service_type AS domain, 'Mobile App (Live)' AS source, 'Mobile Enquiry' AS category, enquiry_for AS interested_in, e.remarks, lead_code, 'Pending payment' AS payment_status, 0 AS total_fees, 0 AS paid_amount, NULL AS invalid_reason, 1 AS is_active, NULL AS deleted_at, NULL AS deleted_by, 'New' AS status, NULL AS primary_status, NULL AS assigned_to, NULL AS assigned_to_name, NULL AS assigned_by, NULL AS assigned_by_name, 'Mobile' AS source_type, NULL AS company_name, created_at, created_at AS updated_at
                FROM bgoi_enquiries e
                WHERE NOT EXISTS (
                    SELECT 1 FROM leads l 
                    WHERE l.source = 'Mobile App (Live)' AND l.student_name = e.candidate_name AND l.created_at = e.created_at
                )
            ) AS combined
        `;

        let sql = `SELECT * FROM ${combinedBase} WHERE 1=1`;
        let params = [];

        // 0. Company Isolation
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            sql += ' AND company_name = ?';
            params.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            sql += ' AND company_name = ?';
            params.push(company_name);
        }

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                sql += " AND (combined.source_type = 'Agent' OR (combined.company_name IS NOT NULL AND combined.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                sql += " AND (combined.source_type = 'Own' OR combined.source_type IS NULL OR combined.source_type = '') AND (combined.company_name IS NULL OR combined.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                sql += ' AND combined.source_type = ?';
                params.push(source_type);
            }
        }

        // --- Apply Filters ---

        // 1. Staff/User Filter
        if (filterUserId && filterUserId !== 'All') {
            sql += " AND assigned_to = ?";
            params.push(filterUserId);
        } else if (isStaffUser(req.user)) {
            sql += " AND assigned_to = ?";
            params.push(loggedUserId);
        }

        // 2. Domain Filter (Security + Selection)
        if (!isSuperAdmin) {
            if (selectedDomain && selectedDomain !== 'All') {
                // Validate selected domain is in user's authorized list
                const authorized = String(userDomain || '').split(',').map(d => d.trim().toLowerCase());
                const effectiveDomain = authorized.includes(selectedDomain.toLowerCase().trim()) ? selectedDomain : userDomain;
                sql = appendDomainCondition(sql, params, effectiveDomain);
            } else {
                sql = appendDomainCondition(sql, params, userDomain);
            }
        } else if (selectedDomain && selectedDomain !== 'All') {
            sql = appendDomainCondition(sql, params, selectedDomain);
        }

        // --- 3. Timeframe filter logic ---
        // For updated statuses, filter by updated_at date instead of created_at date
        const dateCol = filterType === 'updated' ? 'COALESCE(updated_at, created_at)' : 'created_at';

        if (timeframe === 'today' || timeframe === 'history') {
            const filterDate = date || getISTDay();
            sql += ` AND DATE(${dateCol}) = ?`;
            params.push(filterDate);
        } else if (timeframe === 'week') {
            sql += ` AND ${dateCol} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)`;
        } else if (timeframe === 'month') {
            sql += ` AND ${dateCol} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)`;
        } else if (timeframe === 'year') {
            sql += ` AND ${dateCol} >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)`;
        } else if (timeframe === 'custom' && startDate && endDate) {
            sql += ` AND DATE(${dateCol}) BETWEEN ? AND ?`;
            params.push(startDate, endDate);
        }

        if (filterType === 'today') {
            sql += " AND status = 'New'";
        } else if (filterType === 'pending') {
            sql += " AND status = 'Waiting for Confirmation'";
        } else if (filterType === 'updated') {
            sql += " AND COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) != 'New'";
        } else if (filterType === 'invalid') {
            sql += " AND COALESCE(NULLIF(TRIM(status), ''), NULLIF(TRIM(primary_status), '')) IN ('Invalid', 'Not Interested')";
        }

        if (filterType === 'updated') {
            sql += " ORDER BY COALESCE(updated_at, created_at) DESC";
        } else {
            sql += " ORDER BY created_at DESC";
        }

        const [rows] = await pool.execute(sql, params);
        res.json(rows);
    } catch (err) {
        console.error("Dashboard Leads Filter Error:", err);
        res.status(500).json({ error: err.message });
    }
});
// Fetch Leads (Used by DomainPage.jsx)
// Helper to attach overseas details from the new table
async function attachOverseasDetails(leads) {
    if (!leads || leads.length === 0) return leads;
    const leadIds = leads.map(l => l.id).filter(id => id);
    if (leadIds.length === 0) return leads;

    try {
        const [detailsRows] = await pool.query(
            `SELECT lead_id, field_key, field_value FROM lead_overseas_details WHERE lead_id IN (${leadIds.join(',')})`
        );

        const detailsMap = {};
        for (const row of detailsRows) {
            if (!detailsMap[row.lead_id]) detailsMap[row.lead_id] = {};
            detailsMap[row.lead_id][row.field_key] = row.field_value;
        }

        for (const lead of leads) {
            if (detailsMap[lead.id]) {
                let extraFin = {};
                if (typeof lead.extra_financials === 'string') {
                    try { extraFin = JSON.parse(lead.extra_financials) || {}; } catch (e) { }
                } else if (lead.extra_financials) {
                    extraFin = lead.extra_financials;
                }
                extraFin.overseas_details = detailsMap[lead.id];
                lead.extra_financials = JSON.stringify(extraFin);
            }
        }
    } catch (err) {
        console.error("Error attaching overseas details:", err);
    }
    return leads;
}

// Fetch Leads (Used by DomainPage.jsx)
app.get('/api/leads', authenticateToken, async (req, res) => {
    const { domain, assignedTo, view, page = 1, limit = 10, source_type, company_name, source } = req.query;
    const pageNum = parseInt(page, 10) || 1;
    const limitNum = parseInt(limit, 10) || 10;
    const offset = (pageNum - 1) * limitNum;
    const { role, id: userId, domain: userDomain } = req.user;
    try {
        let where = "WHERE is_active = 1";
        const filters = [];
        const isSuperAdmin = isSuperAdminUser(req.user);

        // 0. Company Isolation
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            where += ' AND company_name = ?';
            filters.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            where += ' AND company_name = ?';
            filters.push(company_name);
        }

        const addDomainFilter = (domainValue) => {
            const aliases = getDomainAliases(domainValue);
            if (aliases.length <= 1) {
                where += " AND domain = ?";
                filters.push(aliases[0] || String(domainValue || '').trim());
                return;
            }
            where += ` AND domain IN (${aliases.map(() => '?').join(', ')})`;
            filters.push(...aliases);
        };
        if (isStaffUser(req.user)) {
            if ((req.user.role === 'Document Handler' || req.user.designation === 'Document Handler') &&
                (!domain || domain === 'All' || domain === 'Bluestone Overseas' || domain === 'Overseas')) {
                where += " AND (assigned_to = ? OR domain IN ('Bluestone Overseas', 'Overseas Enrollment', 'Overseas'))";
                filters.push(userId);
            } else {
                where += " AND assigned_to = ?";
                filters.push(userId);
            }
        } else if (getUserTier(req.user) === 'ADMIN') {
            addDomainFilter(userDomain);
        } else if (domain && domain !== 'All') {
            addDomainFilter(domain);
        }
        if (assignedTo && (isSuperAdmin || getUserTier(req.user) === 'ADMIN')) {
            where += " AND assigned_to = ?";
            filters.push(Number(assignedTo));
        }

        // --- Apply View Filters ---
        if (view === 'invalid') {
            where += " AND status = 'Not Interested'";
        } else if (view === 'waiting') {
            where += " AND status = 'Waiting for Confirmation'";
        } else if (view === 'dropped') {
            where += " AND status = 'Dropped'";
        } else if (view === 'payment') {
            where += " AND (payment_status IN ('Advance payment', 'Partial Payment', 'Full payment', 'No Fee'))";
        } else if (view === 'follow-up') {
            where += " AND status = 'Follow Up'";
        }

        // --- Source Type Filter ---
        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                where += " AND (combined.source_type = 'Agent' OR (combined.company_name IS NOT NULL AND combined.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                where += " AND (combined.source_type = 'Own' OR combined.source_type IS NULL OR combined.source_type = '') AND (combined.company_name IS NULL OR combined.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                where += ' AND combined.source_type = ?';
                filters.push(source_type);
            }
        }
        if (source && source !== 'All') {
            where += ' AND (combined.source = ? OR combined.source LIKE ?)';
            filters.push(source, `%${source}%`);
        }

        const baseSql = `
            (
                SELECT 
                    id, student_name, last_name, email, phone, domain, source, category, 
                    interested_in, remarks, lead_code, status, is_active, 
                    payment_status, total_fees, paid_amount,
                    assigned_to, assigned_to_name, assigned_by, assigned_by_name, source_type, company_name, intake, created_at,
                    passport_no, university_student_id, university_name, course_name, primary_status, secondary_status, overseas_pipeline_status
                FROM leads
                WHERE is_active = 1
                
                UNION ALL
                
                SELECT 
                    e.id, 
                    COALESCE(NULLIF(e.candidate_name, ''), NULLIF(e.full_name, ''), u.name, 'Mobile User') AS student_name, 
                    '' AS last_name,
                    e.email AS email, 
                    COALESCE(NULLIF(e.phone, ''), 'M-APP') AS phone, 
                    e.service_type AS domain, 
                    'Mobile App (Live)' AS source, 
                    'Mobile Enquiry' AS category,
                    e.enquiry_for AS interested_in, 
                    e.remarks AS remarks, 
                    CONCAT('M-', e.id) AS lead_code, 
                    'New' AS status, 
                    1 AS is_active,
                    'Pending payment' AS payment_status,
                    0 AS total_fees,
                    0 AS paid_amount,
                    NULL AS assigned_to, 
                    NULL AS assigned_to_name, 
                    NULL AS assigned_by, 
                    NULL AS assigned_by_name, 
                    'Mobile' AS source_type,
                    NULL AS company_name,
                    NULL AS intake,
                    e.created_at,
                    NULL AS passport_no, NULL AS university_student_id, NULL AS university_name, 
                    NULL AS course_name, 
                    NULL AS primary_status, NULL AS secondary_status, NULL AS overseas_pipeline_status
                FROM bgoi_enquiries e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE NOT EXISTS (
                    SELECT 1 FROM leads l 
                    WHERE l.source = 'Mobile App' 
                      AND (l.student_name = e.candidate_name OR l.student_name = e.full_name OR l.student_name = u.name)
                      AND l.created_at = e.created_at
                )
            ) AS combined
        `;
        const dataSql = `SELECT *, (SELECT subject FROM lead_remarks_messages WHERE lead_id = combined.id ORDER BY created_at DESC, id DESC LIMIT 1) as latest_remark_subject 
                        FROM ${baseSql} ${where} 
                        ORDER BY created_at DESC 
                        LIMIT ? OFFSET ?`;
        const countSql = `SELECT COUNT(*) as total FROM ${baseSql} ${where}`;
        const [rows] = await pool.execute(dataSql, [...filters, limitNum, offset]);
        const [countResult] = await pool.execute(countSql, filters);
        const total = countResult[0].total;

        const leadsWithDetails = await attachOverseasDetails(rows);

        res.json({
            leads: leadsWithDetails,
            total,
            page: pageNum,
            limit: limitNum,
            totalPages: Math.ceil(total / limitNum)
        });
    } catch (err) {
        console.error("Lead list SQL ERROR:", err.message);
        res.status(500).json({ error: err.message });
    }
});
// Create Lead (Enquiry Form)
// Register New Staff
app.post('/api/auth/register', authenticateToken, async (req, res) => {
    // Only Admins can register new staff
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Registration restricted to admins" });
    }

    const { employee_id, name, email, phone, role, tier, designation, domain, password, user_type, company_name } = req.body;

    // TIER SECURITY: Regular Admins can only create STAFF tier
    if (!isSuperAdminUser(req.user) && tier !== 'STAFF' && role !== 'Agent Staff') {
        return res.status(403).json({ msg: "Only Super Admins can create Admin/Super Admin accounts" });
    }

    // AGENT/FRANCHISE SECURITY:
    let finalUserType = user_type || 'CORE';
    let finalCompanyName = company_name || null;

    const loggedUserType = req.user.user_type || 'CORE';

    if (loggedUserType === 'AGENT') {
        finalUserType = 'AGENT';
        finalCompanyName = req.user.company_name;
    } else if (loggedUserType === 'FRANCHISE') {
        finalUserType = 'FRANCHISE';
        finalCompanyName = req.user.company_name;
    }

    try {
        // 1. Check if user already exists
        const [existing] = await pool.execute('SELECT id FROM users WHERE email = ?', [email]);
        if (existing.length > 0) {
            return res.status(400).json({ msg: "Staff with this email already exists" });
        }

        // 2. Hash Password
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);

        // 3. Save User
        const nowStr = getISTString();
        const [result] = await pool.execute(
            `INSERT INTO users (employee_id, name, email, phone, role, tier, designation, domain, password, user_type, company_name, is_active, can_export_copy, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0, ?)`,
            [employee_id, name, email, phone, role, tier, designation || null, domain, hashedPassword, finalUserType, finalCompanyName, nowStr]
        );

        res.status(201).json({ msg: "Staff registered successfully", userId: result.insertId });
    } catch (err) {
        console.error("Registration Error:", err);
        res.status(500).json({ error: "Registration failed", details: err.message });
    }
});

async function sendAgentNotification(agentId, leadDetails, isBulk = false, bulkCount = 0) {
    if (!agentId) return;
    try {
        const [users] = await pool.execute('SELECT name, email, company_name FROM users WHERE id = ?', [agentId]);
        if (users.length === 0 || !users[0].email) return;

        const agent = users[0];
        const transporter = createMailTransporter();
        if (!transporter) return;

        let subject = '';
        let html = '';

        if (isBulk) {
            subject = `New Leads Imported - ${agent.company_name || 'Agent Portal'}`;
            html = `
                <div style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
                    <h2 style="color: #0f172a;">Bulk Lead Import Successful</h2>
                    <p>Dear ${agent.name},</p>
                    <p>You have successfully imported <strong>${bulkCount}</strong> new leads via the agent portal.</p>
                    <br/>
                    <p>Best regards,</p>
                    <p><strong>The Bluestone Groups Team</strong></p>
                </div>
            `;
        } else {
            subject = `New Lead Submitted - ${leadDetails.leadCode}`;
            html = `
                <div style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
                    <h2 style="color: #0f172a;">New Lead Submitted</h2>
                    <p>Dear ${agent.name},</p>
                    <p>A new lead has been submitted via the agent portal.</p>
                    <table style="border-collapse: collapse; width: 100%; max-width: 600px; margin-top: 20px;">
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Name:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadDetails.student_name}</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadDetails.email}</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Phone:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadDetails.phone}</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Domain:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadDetails.domain}</td></tr>
                        <tr><td style="padding: 8px; border: 1px solid #ddd;"><strong>Reference Code:</strong></td><td style="padding: 8px; border: 1px solid #ddd;">${leadDetails.leadCode}</td></tr>
                    </table>
                    <br/>
                    <p>Best regards,</p>
                    <p><strong>The Bluestone Groups Team</strong></p>
                </div>
            `;
        }

        const recipients = [agent.email, 'bluestoneocs@gmail.com'].join(',');

        transporter.sendMail({
            from: SMTP_CONFIG.from || SMTP_CONFIG.user,
            to: recipients,
            subject: subject,
            html: html
        }).catch(err => console.error("Agent notification mail send failed:", err.message));

    } catch (err) {
        console.error("Agent notification error:", err.message);
    }
}

const appRoutes = require('./appRoutes');
app.use('/api/app', appRoutes(pool, JWT_SECRET, getNextLeadCode, getISTString, triggerEmails));

app.post('/api/leads', async (req, res) => {
    const {
        student_name, email, phone, alternate_phone, domain, category, source, interested_in, remarks,
        assigned_to, assigned_to_name, assigned_by, assigned_by_name, source_type, company_name, intake, passport_number,
        passport_no, university_student_id, university_name, course_name, primary_status, secondary_status,
        college_contacts
    } = req.body;

    // Optional Auth for Portal users
    let portalUser = null;
    const authHeader = req.headers['authorization'];
    if (authHeader && authHeader.startsWith('Bearer ')) {
        const token = authHeader.split(' ')[1];
        try {
            portalUser = jwt.verify(token, JWT_SECRET);
        } catch (e) {
            // Ignore invalid tokens for public submissions
        }
    }

    try {
        // ✅ NORMALIZE DOMAIN (CRITICAL FIX)
        const normalizedDomain = normalizeContactDomain(domain);

        // ❌ DUPLICATE CHECK (PHONE + DOMAIN)
        const [existing] = await pool.execute(
            'SELECT id, lead_code FROM leads WHERE phone = ? AND domain = ? LIMIT 1',
            [phone, normalizedDomain]
        );
        if (existing.length > 0) {
            return res.status(409).json({
                message: `Duplicate Entry: A lead with phone ${phone} already exists in ${normalizedDomain} (Code: ${existing[0].lead_code}).`
            });
        }
        const leadCode = await getNextLeadCode(normalizedDomain);
        const nowStr = getISTString();

        // 🏢 Tag company_name and source_type
        let finalSourceType = source_type || (portalUser ? (portalUser.user_type === 'AGENT' || String(portalUser.role || '').toLowerCase().includes('agent') ? 'Agent' : portalUser.user_type === 'FRANCHISE' ? 'Franchise' : 'Own') : 'Own');
        let finalCompanyName = company_name || (portalUser ? portalUser.company_name : null);

        // Fallback: If company_name is still null but we have a portal user, fetch from DB
        if (!finalCompanyName && portalUser && portalUser.id) {
            try {
                const [userRows] = await pool.execute('SELECT company_name FROM users WHERE id = ?', [portalUser.id]);
                if (userRows.length > 0 && userRows[0].company_name) {
                    finalCompanyName = userRows[0].company_name;
                }
            } catch (err) {
                console.error("Fallback company fetch failed:", err);
            }
        }

        await pool.execute(
            `INSERT INTO leads 
            (student_name, passport_number, email, phone, alternate_phone, domain, category, source, interested_in, remarks, lead_code, status, is_active, payment_status, assigned_to, assigned_to_name, assigned_by, assigned_by_name, source_type, company_name, intake, created_at,
             passport_no, university_student_id, university_name, course_name, primary_status, secondary_status, college_contacts) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New', 1, 'Pending payment', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                student_name, passport_number || null, email || null, phone || null, alternate_phone || null, normalizedDomain, category || 'Standard Enquiry', source || 'Direct', interested_in || null, remarks || null, leadCode,
                assigned_to || null, assigned_to_name || null, assigned_by || null, assigned_by_name || null, finalSourceType, finalCompanyName, intake || null, nowStr,
                passport_no || null, university_student_id || null, university_name || null, course_name || null, primary_status || null, secondary_status || null,
                college_contacts && Array.isArray(college_contacts) ? JSON.stringify(college_contacts) : null
            ]
        );
        // ✅ AUTO-RESPONDER EMAIL
        if (email && email.trim() !== '') {
            try {
                const transporter = createMailTransporter();
                if (transporter) {
                    const html = `
                        <div style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
                            <h2 style="color: #0f172a;">Thank you for your Enquiry!</h2>
                            <p>Dear ${student_name},</p>
                            <p>We have received your enquiry regarding <strong>${normalizedDomain}</strong> services at Bluestone Groups.</p>
                            <p>Your reference number is <strong>${leadCode}</strong>. One of our representatives will review your request and get back to you shortly.</p>
                            <br/>
                            <p>Best regards,</p>
                            <p><strong>The Bluestone Groups Team</strong></p>
                        </div>
                    `;
                    transporter.sendMail({
                        from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                        to: email.trim(),
                        subject: `Your Enquiry is Received - ${leadCode}`,
                        html
                    }).catch(err => console.error("Auto-responder mail send failed:", err.message));
                }
            } catch (mailErr) {
                console.error("Auto-responder mail configuration error:", mailErr.message);
            }
        }

        // ✅ AGENT NOTIFICATION
        if (portalUser && (portalUser.user_type === 'AGENT' || String(portalUser.role || '').toLowerCase().includes('agent'))) {
            sendAgentNotification(portalUser.id, { student_name, email, phone, domain: normalizedDomain, leadCode });
        }

        // ✅ SUPER ADMIN NOTIFICATION
        await createSystemNotification(`New Enquiry Received: ${student_name} (${normalizedDomain})`, req.user || { name: 'Website Portal' });

        res.status(201).json({ msg: "Lead created", lead_code: leadCode });
    } catch (err) {
        console.error("CREATE LEAD ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});
// Update Status (Used by Dropdown in DomainPage)
app.put('/api/leads/:id/status', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { status } = req.body;
    const changedBy = req.user?.name || 'System';
    try {
        const [beforeRows] = await pool.execute('SELECT status, lead_code, domain, passport_number, passport_no FROM leads WHERE id = ?', [id]);
        if (beforeRows.length === 0) return res.status(404).json({ msg: "Lead not found" });

        const oldStatus = beforeRows[0].status;
        const leadCode = beforeRows[0].lead_code || `#${id}`;
        const domain = beforeRows[0].domain || '';
        const passportNumber = beforeRows[0].passport_number || beforeRows[0].passport_no || '';

        // Validation for Overseas Enrolled
        if (status === 'Enrolled' && domain.toLowerCase().includes('overseas')) {
            if (!passportNumber || !passportNumber.trim()) {
                return res.status(400).json({ msg: "Passport number is mandatory for enrolled overseas candidates" });
            }
        }

        const nowStr = getISTString();
        await pool.execute('UPDATE leads SET status = ?, updated_at = ? WHERE id = ?', [status, nowStr, id]);
        await pool.execute(
            `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, 'STATUS_UPDATE', 'status', ?, ?, ?, ?)`,
            [id, oldStatus || '', status || '', changedBy, nowStr]
        );
        // ✅ SUPER ADMIN NOTIFICATION
        await createSystemNotification(`Status updated to '${status}' for Candidate ${leadCode}`, req.user);

        res.json({ msg: "Updated" });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// Get Payment History
app.get('/api/leads/:id/payment-history', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT action_type, field_name, old_value, new_value, changed_by, changed_at 
             FROM lead_edit_history 
             WHERE lead_id = ? AND action_type = 'PAYMENT_UPDATE' 
             ORDER BY changed_at DESC`,
            [id]
        );
        res.json({ success: true, data: rows });
    } catch (err) {
        console.error("PAYMENT HISTORY ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});

// Update Payment Details (Super Admin, Admin, and Staff)
app.put('/api/leads/:id/payment', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { payment_status, total_fees, paid_amount, extra_financials, payment_date } = req.body;

    // Permissions: Allow Super Admin, Admin, Staff
    if (!isAdminUser(req.user) && !isStaffUser(req.user)) {
        return res.status(403).json({ msg: 'Only Super Admin, Admin and Staff can update payment details' });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT domain, payment_status, total_fees, paid_amount, assigned_to, status, extra_financials FROM leads WHERE id = ?',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: 'Lead not found' });
        const before = beforeRows[0];

        // Security: Ensure user has access to this lead
        if (!canUserAccessLead(before, req.user)) {
            return res.status(403).json({ msg: 'Access Denied: You do not have permission to update this lead' });
        }
        const allowedPaymentStatuses = [
            'Advance payment', 'Partial Payment', 'Full payment', 'No Fee', 'Pending payment',
            'Partial refund', 'Fully refund', 'No refund', 'Refund pending'
        ];
        const nextPaymentStatus = typeof payment_status === 'string' && payment_status.trim() !== ''
            ? payment_status.trim()
            : before.payment_status;

        if (!allowedPaymentStatuses.includes(nextPaymentStatus)) {
            return res.status(400).json({ msg: 'Invalid payment status value' });
        }
        const parseAmount = (value, fallback) => {
            if (value === undefined || value === null || value === '') return Number(fallback || 0);
            const num = Number(value);
            return Number.isFinite(num) ? num : NaN;
        };
        let nextTotalFees = parseAmount(total_fees, before.total_fees);
        let nextPaidAmount = parseAmount(paid_amount, before.paid_amount);

        let finalExtraFinancialsObj = extra_financials;
        if (typeof extra_financials === 'string') {
            try { finalExtraFinancialsObj = JSON.parse(extra_financials); } catch (e) { }
        }

        if (finalExtraFinancialsObj === undefined || finalExtraFinancialsObj === null) {
            finalExtraFinancialsObj = {};
        } else if (typeof finalExtraFinancialsObj !== 'object') {
            finalExtraFinancialsObj = { value: finalExtraFinancialsObj };
        }

        if (payment_date !== undefined) {
            finalExtraFinancialsObj.payment_date = payment_date;
        }

        // Auto-calculate for Overseas Domain
        const shortDomainName = (before.domain || '').replace(/^(Bluestone|BGOI)\\s+/i, '').trim().toLowerCase();
        if (shortDomainName === 'overseas' && finalExtraFinancialsObj && finalExtraFinancialsObj.overseas_milestones) {
            let mTotal = 0;
            let mPaid = 0;
            Object.values(finalExtraFinancialsObj.overseas_milestones).forEach(m => {
                mTotal += Number(m.amount) || 0;
                mPaid += Number(m.paid) || 0;
            });
            nextTotalFees = mTotal;
            nextPaidAmount = mPaid;
        }

        if (Number.isNaN(nextTotalFees) || Number.isNaN(nextPaidAmount)) {
            return res.status(400).json({ msg: 'Fees and paid amount must be numbers' });
        }
        if (nextTotalFees < 0 || nextPaidAmount < 0) {
            return res.status(400).json({ msg: 'Fees and paid amount cannot be negative' });
        }

        // Auto-Enrollment: Set status to 'Enrolled' when payment is updated to anything other than 'Pending payment' or Refund statuses
        const refundStatuses = ['Partial refund', 'Fully refund', 'No refund', 'Refund pending'];
        const nextStatus = (nextPaymentStatus !== 'Pending payment' && !refundStatuses.includes(nextPaymentStatus))
            ? 'Enrolled'
            : before.status;

        // Handle Migration to lead_overseas_details
        if (finalExtraFinancialsObj && finalExtraFinancialsObj.overseas_details) {
            const details = finalExtraFinancialsObj.overseas_details;

            // Delete old details for this lead
            await pool.execute('DELETE FROM lead_overseas_details WHERE lead_id = ?', [id]);

            // Insert new details
            for (const [key, value] of Object.entries(details)) {
                if (value !== undefined && value !== null && value !== '') {
                    await pool.execute(
                        'INSERT INTO lead_overseas_details (lead_id, field_key, field_value) VALUES (?, ?, ?)',
                        [id, key, String(value)]
                    );
                }
            }

            // Remove from JSON payload
            delete finalExtraFinancialsObj.overseas_details;
        }

        const nextExtraFinancials = finalExtraFinancialsObj !== undefined
            ? (typeof finalExtraFinancialsObj === 'object' ? JSON.stringify(finalExtraFinancialsObj) : finalExtraFinancialsObj)
            : before.extra_financials;

        const nowStr = getISTString();

        let nextRefundStatus = before.refund_status || 'Not Applicable';
        let nextRefundAmount = before.refund_amount || 0;

        if (nextPaymentStatus === 'Fully refund') {
            nextRefundStatus = 'Refunded';
            nextRefundAmount = nextPaidAmount; // Full refund equals the total paid amount
        } else if (nextPaymentStatus === 'Refund pending') {
            nextRefundStatus = 'Requested';
        }

        await pool.execute(
            'UPDATE leads SET payment_status = ?, total_fees = ?, paid_amount = ?, status = ?, extra_financials = ?, refund_status = ?, refund_amount = ?, updated_at = ? WHERE id = ?',
            [nextPaymentStatus, nextTotalFees, nextPaidAmount, nextStatus, nextExtraFinancials, nextRefundStatus, nextRefundAmount, nowStr, id]
        );
        const changedBy = req.user?.name || 'System';
        const historyRows = [];

        if (before.status !== nextStatus) {
            historyRows.push([id, 'STATUS_UPDATE', 'status', before.status || '', nextStatus, changedBy]);
        }
        if ((before.payment_status || '') !== (nextPaymentStatus || '')) {
            historyRows.push([id, 'PAYMENT_UPDATE', 'payment_status', before.payment_status || '', nextPaymentStatus || '', changedBy]);
        }
        if (Number(before.total_fees || 0) !== Number(nextTotalFees || 0)) {
            historyRows.push([id, 'PAYMENT_UPDATE', 'total_fees', String(before.total_fees || 0), String(nextTotalFees || 0), changedBy]);
        }
        if (Number(before.paid_amount || 0) !== Number(nextPaidAmount || 0)) {
            historyRows.push([id, 'PAYMENT_UPDATE', 'paid_amount', String(before.paid_amount || 0), String(nextPaidAmount || 0), changedBy]);
        }

        let oldPaymentDate = '';
        try {
            if (before.extra_financials) {
                const parsed = typeof before.extra_financials === 'string' ? JSON.parse(before.extra_financials) : before.extra_financials;
                oldPaymentDate = parsed.payment_date || '';
            }
        } catch (e) { }

        if (payment_date !== undefined && (oldPaymentDate || '') !== (payment_date || '')) {
            historyRows.push([id, 'PAYMENT_UPDATE', 'payment_date', oldPaymentDate || '', payment_date || '', changedBy]);
        }

        for (const h of historyRows) {
            await pool.execute(
                `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [...h, nowStr]
            );
        }

        // ✅ SUPER ADMIN NOTIFICATION
        const [lcRowsFin] = await pool.execute('SELECT lead_code FROM leads WHERE id = ?', [id]);
        const leadCodeFin = lcRowsFin[0]?.lead_code || `#${id}`;
        await createSystemNotification(`Financial details updated for Candidate ${leadCodeFin}`, req.user);

        return res.json({
            msg: 'Payment details updated',
            payment: {
                payment_status: nextPaymentStatus,
                total_fees: nextTotalFees,
                paid_amount: nextPaidAmount,
                status: nextStatus,
                extra_financials: nextExtraFinancials ? JSON.parse(nextExtraFinancials) : null
            }
        });
    } catch (err) {
        return res.status(500).json({ error: err.message });
    }
});

// Super-admin consolidated leads with advanced filters/sort/pagination
app.get('/api/bgi/leads', authenticateToken, async (req, res) => {
    const isSuperAdmin = isSuperAdminUser(req.user);
    const isStaff = isStaffUser(req.user);

    const {
        view = 'all',
        page = 1,
        limit = 10,
        search = '',
        domain = 'All',
        status = 'All',
        payment_status = 'All',
        assigned_to = 'All',
        invalid_reason = 'All',
        source = 'All',
        sort_by = 'created_at',
        sort_order = 'desc',
        today,
        date,
        startDate,
        endDate,
        company_name,
        source_type
    } = req.query;
    const pageNum = Math.max(parseInt(page, 10) || 1, 1);
    const limitNum = Math.max(parseInt(limit, 10) || 10, 1);
    const offset = (pageNum - 1) * limitNum;
    const sortMap = {
        created_at: 'created_at',
        updated_at: 'updated_at',
        student_name: 'student_name',
        domain: 'domain',
        status: 'status',
        payment_status: 'payment_status',
        total_fees: 'total_fees',
        paid_amount: 'paid_amount',
    };
    const safeSortBy = sortMap[sort_by] || 'updated_at';
    const safeSortOrder = String(sort_order).toLowerCase() === 'asc' ? 'ASC' : 'DESC';

    try {
        const combinedBase = `
            (
                SELECT 
                    id, student_name, email, phone, domain, source, category, 
                    interested_in, remarks, lead_code, status, primary_status, is_active, payment_status, 
                    total_fees, paid_amount, invalid_reason,
                    assigned_to, assigned_to_name, assigned_by, assigned_by_name, source_type, company_name, intake, created_at, updated_at
                FROM leads
                WHERE is_active = 1
                
                UNION ALL
                
                SELECT 
                    e.id, 
                    COALESCE(NULLIF(e.candidate_name, ''), NULLIF(e.full_name, ''), 'Mobile User') AS student_name, 
                    e.email AS email, 
                    COALESCE(NULLIF(e.phone, ''), 'M-APP') AS phone, 
                    e.service_type AS domain, 
                    'Mobile App (Live)' AS source, 
                    'Mobile Enquiry' AS category,
                    e.enquiry_for AS interested_in, 
                    e.remarks AS remarks, 
                    CONCAT('M-', e.id) AS lead_code, 
                    'New' AS status,
                    NULL AS primary_status,
                    1 AS is_active,
                    'Pending payment' AS payment_status,
                    0 AS total_fees,
                    0 AS paid_amount,
                    NULL AS invalid_reason,
                    NULL AS assigned_to, 
                    NULL AS assigned_to_name, 
                    NULL AS assigned_by, 
                    NULL AS assigned_by_name, 
                    'Mobile' AS source_type,
                    NULL AS company_name,
                    NULL AS intake,
                    e.created_at,
                    NULL AS updated_at
                FROM bgoi_enquiries e
                WHERE NOT EXISTS (
                    SELECT 1 FROM leads l 
                    WHERE l.source = 'Mobile App (Live)' 
                      AND l.student_name = e.candidate_name 
                      AND l.created_at = e.created_at
                )
            ) AS combined`;
        let where = ' WHERE 1=1';
        const params = [];

        // 0. Enforce Company Isolation for Agents/Franchises
        if (req.user.user_type === 'AGENT' || req.user.user_type === 'FRANCHISE') {
            if (!req.user.company_name) return res.status(403).json({ error: 'User has no company assigned' });
            where += ' AND company_name = ?';
            params.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            where += ' AND company_name = ?';
            params.push(company_name);
        }

        // 1. Enforce Domain Security for non-SuperAdmins
        if (!isSuperAdmin) {
            const { domain: userDomains } = req.user;
            const authorizedAliases = getDomainAliases(userDomains);

            if (domain && domain !== 'All') {
                const requestedAliases = getDomainAliases(domain);
                const hasAccess = requestedAliases.every(a => authorizedAliases.includes(a));
                if (!hasAccess) {
                    return res.status(403).json({ error: 'Access denied to this domain' });
                }
                where = appendDomainCondition(where, params, domain);
            } else {
                where = appendDomainCondition(where, params, userDomains);
            }
        } else if (domain && domain !== 'All') {
            where = appendDomainCondition(where, params, domain);
        }

        // 2. Enforce Assignment Security for Staff
        if (isStaff) {
            where += ' AND assigned_to = ?';
            params.push(req.user.id);
        } else if (assigned_to && assigned_to !== 'All') {
            where += ' AND assigned_to = ?';
            params.push(Number(assigned_to));
        }

        // 3. Additional Filters
        if (status && status !== 'All') {
            const statuses = status.split(',').map(s => s.trim());
            if (statuses.length === 1) {
                where += ' AND (status = ? OR primary_status = ?)';
                params.push(statuses[0], statuses[0]);
            } else {
                const placeholders = statuses.map(() => '?').join(', ');
                where += ` AND (status IN (${placeholders}) OR primary_status IN (${placeholders}))`;
                params.push(...statuses, ...statuses);
            }
        }
        if (payment_status && payment_status !== 'All') {
            where += ' AND payment_status = ?';
            params.push(payment_status);
        }
        if (invalid_reason && invalid_reason !== 'All') {
            where += ' AND invalid_reason = ?';
            params.push(invalid_reason);
        }
        if (source && source !== 'All') {
            where += ' AND (source = ? OR source LIKE ?)';
            params.push(source, `%${source}%`);
        }

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                where += " AND (combined.source_type = 'Agent' OR (combined.company_name IS NOT NULL AND combined.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                where += " AND (COALESCE(combined.source_type, 'Own') = 'Own') AND (combined.company_name IS NULL OR combined.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                where += ' AND combined.source_type = ?';
                params.push(source_type);
            }
        }

        // 4. Date Range Filters
        if (today === '1') {
            where += " AND DATE(created_at) = CURDATE()";
        } else if (date) {
            where += " AND DATE(created_at) = ?";
            params.push(date);
        } else if (startDate && endDate) {
            where += " AND DATE(created_at) BETWEEN ? AND ?";
            params.push(startDate, endDate);
        }

        // 5. Search
        const q = String(search || '').trim();
        if (q) {
            const like = `%${q}%`;
            where += ` AND (
                student_name LIKE ? OR
                email LIKE ? OR
                phone LIKE ? OR
                domain LIKE ? OR
                lead_code LIKE ? OR
                status LIKE ? OR
                remarks LIKE ? OR
                source LIKE ? OR
                company_name LIKE ? OR
                intake LIKE ?
            )`;
            params.push(like, like, like, like, like, like, like, like, like, like);
        }

        // 6. View Specifics
        if (view === 'pending') {
            where += ` AND (assigned_to IS NULL OR TRIM(COALESCE(remarks, '')) = '') AND status != 'Enrolled'`;
        } else if (view === 'payment') {
            where += ` AND payment_status IN ('Advance payment', 'Partial Payment', 'Full payment', 'No Fee')`;
        } else if (view === 'invalid') {
            where += ` AND (status IN ('Invalid', 'Not Interested') OR primary_status IN ('Invalid', 'Not Interested'))`;
        }

        const countSql = `SELECT COUNT(*) AS total FROM ${combinedBase} ${where}`;
        const [countRows] = await pool.execute(countSql, params);
        const total = countRows[0]?.total || 0;

        // ✅ PERFORMANCE: Use LEFT JOIN instead of correlated subquery in BGI leads endpoint
        const listSql = `
            SELECT combined.*,
                lrm_sub.latest_remark_subject
            FROM ${combinedBase}
            LEFT JOIN (
                SELECT lead_id,
                    SUBSTRING_INDEX(GROUP_CONCAT(subject ORDER BY created_at DESC, id DESC SEPARATOR '|||'), '|||', 1) AS latest_remark_subject
                FROM lead_remarks_messages
                GROUP BY lead_id
            ) lrm_sub ON lrm_sub.lead_id = combined.id
            ${where}
            ORDER BY ${safeSortBy === 'updated_at' ? 'COALESCE(combined.updated_at, combined.created_at)' : `combined.${safeSortBy}`} ${safeSortOrder}
            LIMIT ? OFFSET ?
        `;
        const [rows] = await pool.execute(listSql, [...params, limitNum, offset]);

        return res.json({
            leads: rows,
            total,
            page: pageNum,
            limit: limitNum,
            totalPages: Math.ceil(total / limitNum) || 1,
        });
    } catch (err) {
        console.error("BGI Leads API ERROR:", err.message);
        console.error("SQL:", listSql);
        console.error("PARAMS:", params);
        return res.status(500).json({ error: err.message });
    }
});
// Update Remarks
app.put('/api/leads/remarks', authenticateToken, async (req, res) => {
    const { leadId, remarks } = req.body;
    const userName = req.user?.name || 'Staff'; // Use fallback to prevent SQL crash
    if (!leadId || typeof remarks !== 'string') {
        return res.status(400).json({ success: false, error: 'A lead ID and remarks text are required.' });
    }
    try {
        const [beforeRows] = await pool.execute('SELECT remarks, lead_code FROM leads WHERE id = ?', [leadId]);
        if (beforeRows.length === 0) {
            return res.status(404).json({ success: false, error: 'Lead not found.' });
        }
        const oldRemarks = beforeRows[0].remarks || '';
        const leadCodeRmk = beforeRows[0].lead_code || `#${leadId}`;
        const nowStr = getISTString();
        await pool.execute('UPDATE leads SET remarks = ?, updated_at = ? WHERE id = ?', [remarks, nowStr, leadId]);
        await recordLeadEditHistory(leadId, 'remarks', oldRemarks, remarks, userName, 'REMARKS_UPDATE');

        try {
            await pool.execute(
                'INSERT INTO remarks_history (lead_id, old_remarks, new_remarks, changed_by, changed_at) VALUES (?, ?, ?, ?, ?)',
                [leadId, oldRemarks, remarks, userName, nowStr]
            );
        } catch (historyErr) {
            console.error("Remarks history archive failed:", historyErr.message);
        }

        // ✅ SUPER ADMIN NOTIFICATION
        await createSystemNotification(`Initial Remarks updated for Candidate ${leadCodeRmk}`, req.user);

        // 3. Send a clean JSON response
        return res.status(200).json({
            success: true,
            msg: "Remarks updated successfully",
            remarks
        });
    } catch (err) {
        console.error("Main Update Error:", err.message);
        return res.status(500).json({ success: false, error: err.message });
    }
});

// Update Franchise Comments
app.put('/api/leads/franchise-comments', authenticateToken, async (req, res) => {
    const { leadId, franchise_comments } = req.body;
    const userName = req.user?.name || 'Staff';
    try {
        const [beforeRows] = await pool.execute('SELECT franchise_comments, lead_code FROM leads WHERE id = ?', [leadId]);
        const oldComments = beforeRows[0]?.franchise_comments || '';
        const leadCode = beforeRows[0]?.lead_code || `#${leadId}`;
        const nowStr = getISTString();

        await pool.execute('UPDATE leads SET franchise_comments = ?, updated_at = ? WHERE id = ?', [franchise_comments || '', nowStr, leadId]);

        try {
            await pool.execute(
                `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
                 VALUES (?, 'FRANCHISE_COMMENTS_UPDATE', 'franchise_comments', ?, ?, ?, ?)`,
                [leadId, oldComments, franchise_comments || '', userName, nowStr]
            );
        } catch (historyErr) {
            console.error("History log failed, but comments were saved:", historyErr.message);
        }

        await createSystemNotification(`Franchise Comments updated for Candidate ${leadCode}`, req.user);

        return res.status(200).json({
            success: true,
            msg: "Franchise comments updated successfully",
            franchise_comments: franchise_comments
        });
    } catch (err) {
        console.error("Franchise Comments Update Error:", err.message);
        return res.status(500).json({ success: false, error: err.message });
    }
});
// Dashboard Summary
app.get('/api/dashboard/summary', authenticateToken, async (req, res) => {
    const { id, role, domain } = req.user;
    const { page = 1, limit = 10 } = req.query;
    const pageNum = parseInt(page);
    const limitNum = parseInt(limit);
    const offset = (pageNum - 1) * limitNum;

    try {
        let leadsQuery = 'SELECT * FROM leads WHERE is_active = 1';
        let countQuery = 'SELECT COUNT(*) as total FROM leads WHERE is_active = 1';
        // Find your existing statsQuery inside app.get('/api/dashboard/summary')
        // REPLACE it with this version:
        let statsQuery = `SELECT 
    -- Lifetime Stats
    COUNT(*) as total,
    COUNT(CASE WHEN status = 'Follow Up' THEN 1 END) as followUp,
    COUNT(CASE WHEN status = 'Enrolled' THEN 1 END) as enrolled,
    -- Today's Stats
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as todayTotal,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() AND status = 'Follow Up' THEN 1 END) as todayFollowUp,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() AND status = 'Enrolled' THEN 1 END) as todayEnrolled
    FROM leads WHERE is_active = 1`;
        let params = [];
        let countParams = [];
        if (!isSuperAdminUser(req.user)) {
            if (getUserTier(req.user) === 'ADMIN') {
                leadsQuery = appendDomainCondition(leadsQuery, params, domain);
                countQuery = appendDomainCondition(countQuery, countParams, domain);
                statsQuery = appendDomainCondition(statsQuery, countParams, domain);
            } else {
                leadsQuery += ' AND assigned_to = ?';
                countQuery += ' AND assigned_to = ?';
                statsQuery += ' AND assigned_to = ?';
                params.push(id);
                countParams.push(id);
            }
        }
        leadsQuery += ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
        params.push(limitNum, offset);
        const [leads] = await pool.execute(leadsQuery, params);
        const [countResult] = await pool.execute(countQuery, countParams);
        const [stats] = await pool.execute(statsQuery, countParams);
        const total = countResult[0].total;

        res.json({
            leads,
            stats: stats[0],
            total,
            page: pageNum,
            limit: limitNum,
            totalPages: Math.ceil(total / limitNum)
        });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
// Fetch TLs for a domain (for multi-TL selection)
app.get('/api/tl-list', authenticateToken, async (req, res) => {
    try {
        const { domain: userDomain } = req.user;
        const { domain: queryDomain, company_name, source_type } = req.query;

        // Determine which domain(s) to filter by
        const filterDomain = (queryDomain && queryDomain !== 'All' && queryDomain !== 'Lead Center')
            ? queryDomain
            : (!isSuperAdminUser(req.user) ? userDomain : null);

        let query = `
            SELECT u.id, u.name, u.role, u.tier
            FROM users u
            LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
            WHERE u.is_active = 1 
            AND (COALESCE(h.tier, 'ADMIN') IN ('ADMIN', 'SUPER_ADMIN'))
        `;
        let params = [];

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                query += " AND u.company_name IS NOT NULL AND u.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')";
            } else if (source_type === 'Own') {
                query += " AND (u.company_name IS NULL OR u.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            }
        }
        // Only TL/Management can see other management personnel
        if (!isAdminUser(req.user)) {
            return res.status(403).json({ msg: "Unauthorized" });
        }

        // Filter by domain
        if (filterDomain) {
            query = appendDomainCondition(query, params, filterDomain);
        }

        const userType = req.user.user_type || 'CORE';
        if (userType === 'AGENT' || userType === 'FRANCHISE') {
            query += " AND u.company_name = ?";
            params.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            query += " AND u.company_name = ?";
            params.push(company_name);
        }

        query += ' ORDER BY u.name ASC';
        const [tls] = await pool.execute(query, params);
        res.json(tls);
    } catch (err) {
        console.error("Fetch TLs Error:", err);
        res.status(500).json({ error: "Could not fetch management users" });
    }
});
// Fetch Staff for TL
app.get('/api/staff-list', authenticateToken, async (req, res) => {
    try {
        const { domain: userDomain } = req.user;
        const { domain: queryDomain, company_name, source_type } = req.query;

        // Determine which domain(s) to filter by
        const filterDomain = (queryDomain && queryDomain !== 'All' && queryDomain !== 'Lead Center')
            ? queryDomain
            : (!isSuperAdminUser(req.user) ? userDomain : null);

        let query = `
            SELECT u.id, u.employee_id, u.name, u.domain, u.tier
            FROM users u
            LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
            WHERE u.is_active = 1 AND COALESCE(h.tier, 'STAFF') = 'STAFF'
        `;
        const params = [];

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                query += " AND u.company_name IS NOT NULL AND u.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')";
            } else if (source_type === 'Own') {
                query += " AND (u.company_name IS NULL OR u.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            }
        }

        if (filterDomain) {
            // Get all aliases for the requested domain (e.g. "Overseas" → ["Overseas", "Bluestone Overseas", ...])
            const aliases = getDomainAliases(filterDomain);
            // Use LIKE matching so that staff with comma-separated domains
            // (e.g. "Overseas, IAS Academy") still appear for each domain they belong to
            const likeClauses = aliases.map(() => 'u.domain LIKE ?').join(' OR ');
            query += ` AND (${likeClauses})`;
            aliases.forEach(alias => params.push(`%${alias}%`));
        }

        const userType = req.user.user_type || 'CORE';
        if (userType === 'AGENT' || userType === 'FRANCHISE') {
            query += " AND u.company_name = ?";
            params.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            query += " AND u.company_name = ?";
            params.push(company_name);
        }

        query += ' ORDER BY u.name ASC';
        const [staff] = await pool.execute(query, params);
        res.json(staff);
    } catch (err) {
        res.status(500).json({ error: "Could not fetch staff" });
    }
});
// Assign Lead
app.post('/api/leads/assign', authenticateToken, async (req, res) => {
    const { leadId, staffId, staffName } = req.body;
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized" });
    }
    try {
        // Get the assigner's name from the authenticated user
        const [assignerData] = await pool.execute('SELECT name FROM users WHERE id = ? AND is_active = 1', [req.user.id]);
        const assignerName = assignerData[0]?.name || 'Unknown';

        const [beforeRows] = await pool.execute(
            'SELECT assigned_to_name FROM leads WHERE id = ?',
            [leadId]
        );
        const oldAssignedTo = beforeRows[0]?.assigned_to_name || '';
        await pool.execute(
            'UPDATE leads SET assigned_to = ?, assigned_to_name = ?, assigned_by = ?, assigned_by_name = ? WHERE id = ?',
            [staffId, staffName, req.user.id, assignerName, leadId]
        );
        const nowStr = getISTString();
        await pool.execute(
            `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, 'ASSIGNMENT_UPDATE', 'assigned_to_name', ?, ?, ?, ?)`,
            [leadId, oldAssignedTo, staffName || '', assignerName, nowStr]
        );
        res.json({ msg: "Lead assigned successfully" });
    } catch (err) {
        res.status(500).json({ error: "Assignment failed" });
    }
});
// Auto-assign leads that don't have assigned_by info yet (for backward compatibility)
app.get('/api/leads/assign-by/:userId', authenticateToken, async (req, res) => {
    const { userId } = req.params;
    try {
        // Find ADMIN-tier user(s) for this staff member's domain
        const [staffData] = await pool.execute('SELECT domain FROM users WHERE id = ? AND is_active = 1', [userId]);
        if (staffData.length === 0) return res.status(404).json({ error: "Staff not found" });

        const staffDomain = staffData[0].domain;

        // Find ALL ADMIN-tier users in the same domain
        const [tlData] = await pool.execute(
            `SELECT u.id, u.name
             FROM users u
             LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
             WHERE u.domain = ? AND u.is_active = 1 AND COALESCE(h.tier, 'STAFF') = 'ADMIN'`,
            [staffDomain]
        );

        // If only 1 ADMIN in domain, auto-assign
        if (tlData.length === 1) {
            const tlId = tlData[0].id;
            const tlName = tlData[0].name;

            // Update any unassigned leads for this staff to have the ADMIN as assigned_by
            await pool.execute(
                'UPDATE leads SET assigned_by = ?, assigned_by_name = ? WHERE assigned_to = ? AND assigned_by IS NULL',
                [tlId, tlName, userId]
            );
            res.json({ msg: "Leads updated", tl: tlName });
        } else if (tlData.length > 1) {
            // If multiple ADMIN users, return the list and let frontend handle it
            res.json({ msg: "Multiple admins in domain", tls: tlData, staffId: userId });
        } else {
            res.json({ msg: "No admin found in domain" });
        }
    } catch (err) {
        res.status(500).json({ error: "Auto-assign failed" });
    }
});
app.get('/api/search/live', authenticateToken, async (req, res) => {
    const { q } = req.query;
    const { domain: userDomain, id: userId } = req.user;
    try {
        const query = (q || '').trim();
        if (!query) return res.json([]);
        const like = `%${query}%`;
        let sql = `
            SELECT 
                id, student_name, domain, phone, email, source, category, interested_in, 
                status, assigned_to_name, assigned_by_name, remarks, lead_code, intake, created_at,
                (SELECT subject FROM lead_remarks_messages WHERE lead_id = leads.id ORDER BY created_at DESC, id DESC LIMIT 1) as latest_remark_subject
            FROM leads 
            WHERE is_active = 1 AND (
                student_name LIKE ? OR
                phone LIKE ? OR
                email LIKE ? OR
                domain LIKE ? OR
                source LIKE ? OR
                category LIKE ? OR
                interested_in LIKE ? OR
                status LIKE ? OR
                assigned_to_name LIKE ? OR
                assigned_by_name LIKE ? OR
                remarks LIKE ? OR
                lead_code LIKE ? OR
                intake LIKE ? OR
                CAST(id AS CHAR) LIKE ?
            )
        `;
        let params = [
            like, like, like, like, like,
            like, like, like, like, like,
            like, like, like, like
        ];
        if (isStaffUser(req.user)) {
            sql += " AND assigned_to = ?";
            params.push(userId);
        } else if (!isSuperAdminUser(req.user)) {
            sql = appendDomainCondition(sql, params, userDomain);
        }
        const [rows] = await pool.execute(
            sql + " ORDER BY created_at DESC LIMIT 25",
            params
        );
        res.json(rows);
    } catch (err) {
        console.error("LIVE SEARCH ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});
app.post('/api/leads/bulk', authenticateToken, async (req, res) => {
    const { leads } = req.body; // Array of lead objects

    if (!leads || !Array.isArray(leads)) {
        return res.status(400).json({ error: "No data provided" });
    }
    const connection = await pool.getConnection();
    try {
        const skipped = [];
        const uniqueLeads = [];
        const seenInFile = new Set();

        for (const l of leads) {
            const normDom = normalizeContactDomain(String(l.domain || '').trim());
            const rawPhone = String(l.phone || '').trim();
            // Normalize phone: keep leading +, strip all non-digits
            const hasPlus = rawPhone.startsWith('+');
            const digits = rawPhone.replace(/[^\d]/g, '');
            const phone = hasPlus ? '+' + digits : digits;

            if (!phone || digits.length < 7 || !normDom) {
                skipped.push({ name: l.student_name, phone: rawPhone, domain: normDom, reason: !normDom ? "Missing domain" : "Invalid/missing phone" });
                continue;
            }

            const combo = `${phone}|${normDom}`;
            if (seenInFile.has(combo)) {
                skipped.push({ name: l.student_name, phone, domain: normDom, reason: "Duplicate in import file" });
                continue;
            }
            seenInFile.add(combo);

            const [existing] = await pool.execute(
                'SELECT id FROM leads WHERE phone = ? AND domain = ? LIMIT 1',
                [phone, normDom]
            );

            if (existing.length > 0) {
                skipped.push({ name: l.student_name, phone, domain: normDom, reason: "Already exists in database" });
                continue;
            }

            uniqueLeads.push({ ...l, phone, domain: normDom });
        }

        if (uniqueLeads.length === 0) {
            return res.json({
                message: "No new leads to import",
                count: 0,
                skippedCount: skipped.length,
                skipped
            });
        }

        await connection.beginTransaction();
        const nowStr = getISTString();

        // Use a loop or batch query for codes to avoid huge concurrency
        const values = [];
        const domainCounters = {}; // Cache to prevent duplicate lead codes during bulk insert

        for (const l of uniqueLeads) {
            const prefix = getLeadCodePrefix(l.domain);
            let leadCode;

            if (domainCounters[prefix] !== undefined) {
                domainCounters[prefix]++;
                leadCode = `${prefix}-${String(domainCounters[prefix]).padStart(4, '0')}`;
            } else {
                leadCode = await getNextLeadCode(l.domain, connection);
                const lastNum = parseInt(String(leadCode).split('-')[1], 10);
                domainCounters[prefix] = lastNum;
            }

            values.push([
                l.student_name,
                l.email,
                l.phone,
                l.domain,
                l.source || 'Bulk Import',
                l.category,
                l.interested_in,
                l.remarks,
                leadCode,
                'New',
                1,
                l.assigned_to || null,
                l.assigned_to_name || null,
                l.assigned_by || null,
                l.assigned_by_name || null,
                l.company_name || req.user.company_name || null,
                l.source_type || (req.user.user_type === 'AGENT' || String(req.user.role || '').toLowerCase().includes('agent') ? 'Agent' : req.user.user_type === 'FRANCHISE' ? 'Franchise' : 'Own'),
                nowStr
            ]);
        }

        const sql = `
            INSERT INTO leads 
            (student_name, email, phone, domain, source, category, interested_in, remarks, lead_code, status, is_active, assigned_to, assigned_to_name, assigned_by, assigned_by_name, company_name, source_type, created_at) 
            VALUES ?
        `;
        await connection.query(sql, [values]);
        await connection.commit();

        // ✅ AGENT NOTIFICATION
        if (req.user && (req.user.user_type === 'AGENT' || String(req.user.role || '').toLowerCase().includes('agent'))) {
            sendAgentNotification(req.user.id, null, true, values.length);
        }

        res.json({
            message: `Imported ${values.length} leads successfully.`,
            count: values.length,
            skippedCount: skipped.length,
            skipped
        });
    } catch (err) {
        console.error("BULK IMPORT ERROR:", err);
        if (connection && typeof connection.rollback === 'function') {
            try { await connection.rollback(); } catch (rbErr) { console.error("Rollback failed:", rbErr.message); }
        }
        res.status(500).json({ error: "Database error during bulk import: " + err.message });
    } finally {
        if (connection) connection.release();
    }
});
app.get('/api/leads/domain/:domainName', authenticateToken, async (req, res) => {
    const { domainName } = req.params;
    const {
        page = 1,
        limit = 10,
        category = 'All',
        interest = 'All',
        status = 'All',
        search = '',
        assigned_to = 'All',
        sortBy = 'updated_at',
        sortOrder = 'desc',
        today,
        date,
        startDate,
        endDate,
        company_name,
        source_type,
        source
    } = req.query;
    const pageNum = parseInt(page) || 1;
    const limitNum = parseInt(limit) || 10;
    const offset = (pageNum - 1) * limitNum;
    const { role, id: userId, domain: userDomain } = req.user;
    try {
        let where = 'WHERE is_active = 1';
        let params = [];

        // Company Isolation
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            where += ' AND company_name = ?';
            params.push(req.user.company_name);
        } else if (company_name && company_name !== 'All') {
            where += ' AND company_name = ?';
            params.push(company_name);
        }

        // ✅ FIXED DOMAIN MATCH (NO NORMALIZE HERE)
        if (domainName !== 'Lead Center' && domainName !== 'All') {
            const aliases = getDomainAliases(domainName);
            where += ` AND domain IN (${aliases.map(() => '?').join(', ')})`;
            params.push(...aliases);
        }
        // ROLE
        if (isStaffUser(req.user)) {
            if ((req.user.role === 'Document Handler' || req.user.designation === 'Document Handler') &&
                (domainName === 'All' || domainName === 'Bluestone Overseas' || domainName === 'Overseas')) {
                where += " AND (assigned_to = ? OR domain IN ('Bluestone Overseas', 'Overseas Enrollment', 'Overseas'))";
                params.push(userId);
            } else {
                where += ' AND assigned_to = ?';
                params.push(userId);
            }
        } else if (assigned_to && assigned_to !== 'All') {
            where += ' AND assigned_to = ?';
            params.push(Number(assigned_to));
        }
        // SEARCH
        if (search.trim()) {
            const like = `%${search}%`;
            where += ` AND (
                student_name LIKE ? OR
                phone LIKE ? OR
                email LIKE ? OR
                lead_code LIKE ? OR
                remarks LIKE ? OR
                source LIKE ? OR
                company_name LIKE ? OR
                intake LIKE ?
            )`;
            params.push(like, like, like, like, like, like, like, like);
        }
        // FILTERS
        if (status !== 'All') {
            const statuses = status.split(',').map(s => s.trim());
            if (statuses.length === 1) {
                where += ' AND (status = ? OR primary_status = ?)';
                params.push(statuses[0], statuses[0]);
            } else {
                const placeholders = statuses.map(() => '?').join(', ');
                where += ` AND (status IN (${placeholders}) OR primary_status IN (${placeholders}))`;
                params.push(...statuses, ...statuses);
            }
        }
        if (category !== 'All') {
            where += ' AND category = ?';
            params.push(category);
        }
        if (interest !== 'All') {
            where += ' AND interested_in = ?';
            params.push(interest);
        }
        if (req.query.payment_status && req.query.payment_status !== 'All') {
            const ps = req.query.payment_status;
            if (ps === 'Advance payment,Partial Payment,Full payment,No Fee' || ps === 'payment') {
                where += " AND payment_status IN ('Advance payment', 'Partial Payment', 'Full payment', 'No Fee')";
            } else {
                where += ' AND payment_status = ?';
                params.push(ps);
            }
        }
        if (req.query.pipeline_status && req.query.pipeline_status !== 'All') {
            const pls = req.query.pipeline_status;
            if (pls === 'processed') {
                where += " AND status = 'Enrolled' AND overseas_pipeline_status != 'Document Collection'";
            } else if (pls === 'cas_team') {
                where += " AND status = 'Enrolled' AND overseas_pipeline_status = 'CAS / I-20 Processing'";
            } else if (pls === 'completed') {
                where += " AND status = 'Closed' AND overseas_pipeline_status = 'Completed'";
            } else {
                where += ' AND overseas_pipeline_status = ?';
                params.push(pls);
            }
        }
        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                where += " AND (source_type = 'Agent' OR (company_name IS NOT NULL AND company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                where += " AND (COALESCE(source_type, 'Own') = 'Own') AND (company_name IS NULL OR company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                where += ' AND source_type = ?';
                params.push(source_type);
            }
        }
        if (source && source !== 'All') {
            where += ' AND (source = ? OR source LIKE ?)';
            params.push(source, `%${source}%`);
        }

        // DATE FILTERS
        if (today === '1') {
            where += " AND DATE(created_at) = CURDATE()";
        } else if (date) {
            where += " AND DATE(created_at) = ?";
            params.push(date);
        } else if (startDate && endDate) {
            where += " AND DATE(created_at) BETWEEN ? AND ?";
            params.push(startDate, endDate);
        }

        const validSortBy = [
            'created_at', 'updated_at', 'student_name', 'phone', 'email', 'status', 'category', 'interested_in', 'lead_code', 'intake'
        ].includes(sortBy) ? sortBy : 'updated_at';
        const validSortOrder = ['asc', 'desc'].includes(sortOrder.toLowerCase()) ? sortOrder.toUpperCase() : 'DESC';
        const [countRows] = await pool.execute(
            `SELECT COUNT(*) as total FROM leads ${where}`,
            params
        );

        // ✅ PERFORMANCE: Use LEFT JOIN instead of correlated subqueries (N+1 → single JOIN)
        let orderByClause;
        if (validSortBy === 'updated_at') {
            orderByClause = `GREATEST(COALESCE(lrm_latest.latest_remark_at, l.created_at), COALESCE(l.updated_at, l.created_at)) ${validSortOrder}`;
        } else {
            orderByClause = `l.${validSortBy} ${validSortOrder}`;
        }

        const [rows] = await pool.execute(
            `SELECT l.*,
                lrm_latest.latest_remark_subject,
                GREATEST(COALESCE(lrm_latest.latest_remark_at, l.created_at), COALESCE(l.updated_at, l.created_at)) AS updated_at
             FROM leads l
             LEFT JOIN (
                SELECT lead_id,
                    MAX(created_at) AS latest_remark_at,
                    SUBSTRING_INDEX(GROUP_CONCAT(subject ORDER BY created_at DESC, id DESC SEPARATOR '|||'), '|||', 1) AS latest_remark_subject
                FROM lead_remarks_messages
                GROUP BY lead_id
             ) lrm_latest ON lrm_latest.lead_id = l.id
             ${where.replace(/FROM leads/g, 'FROM leads l')}
             ORDER BY ${orderByClause}
             LIMIT ? OFFSET ?`,
            [...params, limitNum, offset]
        );
        const leadsWithDetails = await attachOverseasDetails(rows);
        res.json({
            leads: leadsWithDetails,
            pagination: {
                total: countRows[0].total,
                currentPage: pageNum,
                totalPages: Math.ceil(countRows[0].total / limitNum),
                pageSize: limitNum
            }
        });
    } catch (err) {
        console.error("DOMAIN ERROR:", err);
        res.status(500).json({ error: err.message });
    }
});
app.get('/api/leads/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute(
            'SELECT * FROM leads WHERE id = ? AND is_active = 1',
            [id]
        );
        if (rows.length === 0) {
            return res.status(404).json({ message: "Lead not found" });
        }
        const lead = rows[0];
        if (!canUserAccessLead(lead, req.user)) {
            return res.status(403).json({ message: "Access denied" });
        }

        const leadsWithDetails = await attachOverseasDetails([lead]);

        res.json(leadsWithDetails[0]);
    } catch (err) {
        console.error("Lead details fetch error:", err);
        res.status(500).json({ error: "Failed to fetch lead details" });
    }
});

// Bulk Assign Leads
app.post('/api/leads/bulk-assign', authenticateToken, async (req, res) => {
    const { leadIds, staffId, staffName } = req.body;
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized" });
    }
    if (!leadIds || !Array.isArray(leadIds) || leadIds.length === 0) {
        return res.status(400).json({ msg: "No lead IDs provided" });
    }

    let connection;
    try {
        connection = await pool.getConnection();
        await connection.beginTransaction();

        // Get the assigner's name
        const [assignerData] = await connection.execute('SELECT name FROM users WHERE id = ? AND is_active = 1', [req.user.id]);
        const assignerName = assignerData[0]?.name || 'Unknown';

        for (const leadId of leadIds) {
            // Get previous assignment for history
            const [beforeRows] = await connection.execute(
                'SELECT assigned_to_name FROM leads WHERE id = ?',
                [leadId]
            );
            const oldAssignedTo = beforeRows[0]?.assigned_to_name || '';

            // Update lead
            await connection.execute(
                'UPDATE leads SET assigned_to = ?, assigned_to_name = ?, assigned_by = ?, assigned_by_name = ? WHERE id = ?',
                [staffId, staffName, req.user.id, assignerName, leadId]
            );

            const nowStr = getISTString();
            // Add to history
            await connection.execute(
                `INSERT INTO lead_edit_history (lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
                 VALUES (?, 'ASSIGNMENT_UPDATE', 'assigned_to_name', ?, ?, ?, ?)`,
                [leadId, oldAssignedTo, staffName || '', assignerName, nowStr]
            );
        }

        await connection.commit();
        res.json({ msg: `${leadIds.length} lead(s) assigned successfully` });
    } catch (err) {
        if (connection) await connection.rollback();
        console.error("BULK ASSIGN ERROR:", err);
        res.status(500).json({ error: "Bulk assignment failed" });
    } finally {
        if (connection) connection.release();
    }
});

app.get('/api/agent-mail', authenticateToken, async (req, res) => {
    try {
        const { company_name } = req.query;
        const loggedUserType = req.user.user_type || 'CORE';
        const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);

        let targetCompany = (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE')
            ? req.user.company_name
            : company_name;

        let sql = 'SELECT * FROM agent_mail_management WHERE is_active = 1';
        let params = [];

        if (targetCompany) {
            sql += ' AND company_name = ?';
            params.push(targetCompany);
        } else if (!isSuperAdmin) {
            // Non-SuperAdmin must have a target company (usually themselves)
            return res.json([]);
        }

        sql += ' ORDER BY created_at DESC';
        const [rows] = await pool.execute(sql, params);
        res.json(rows);
    } catch (err) {
        console.error("Agent Mail Fetch Error:", err);
        res.status(500).json({ error: err.message });
    }
});

app.post('/api/agent-mail', authenticateToken, async (req, res) => {
    try {
        const { email, company_name } = req.body;
        const loggedUserType = req.user.user_type || 'CORE';
        const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);

        const targetCompany = (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE')
            ? req.user.company_name
            : (company_name || (isSuperAdmin ? 'Bluestone' : null));

        if (!targetCompany || !email) return res.status(400).json({ msg: "Company name and email required" });

        await pool.execute(
            'INSERT INTO agent_mail_management (company_name, email) VALUES (?, ?)',
            [targetCompany, email]
        );
        res.json({ msg: "Recipient added successfully" });
    } catch (err) {
        console.error("Agent Mail Add Error:", err);
        res.status(500).json({ error: err.message });
    }
});

app.delete('/api/agent-mail/:id', authenticateToken, async (req, res) => {
    try {
        const { id } = req.params;
        const loggedUserType = req.user.user_type || 'CORE';

        let sql = 'DELETE FROM agent_mail_management WHERE id = ?';
        let params = [id];

        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            sql += ' AND company_name = ?';
            params.push(req.user.company_name);
        }

        await pool.execute(sql, params);
        res.json({ msg: "Recipient deleted successfully" });
    } catch (err) {
        console.error("Agent Mail Delete Error:", err);
        res.status(500).json({ error: err.message });
    }
});

app.get('/api/leads/:id/domain-staff', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [leadRows] = await pool.execute(
            'SELECT id, domain, assigned_to, company_name, is_active FROM leads WHERE id = ? AND is_active = 1',
            [id]
        );
        if (leadRows.length === 0) return res.status(404).json({ msg: 'Lead not found' });
        const lead = leadRows[0];
        if (!canUserAccessLead(lead, req.user)) return res.status(403).json({ msg: 'Access denied' });
        const aliases = getDomainAliases(lead.domain);
        if (aliases.length === 0) return res.json([]);

        const likeClauses = aliases.map(() => 'u.domain LIKE ?').join(' OR ');
        const likeParams = aliases.map(a => `%${a}%`);

        let companyFilter = "";
        const loggedUserType = req.user.user_type || 'CORE';

        // Determine which company to filter by
        // 1. If lead belongs to an agent company
        const leadCompany = lead.company_name;
        const isAgentLead = leadCompany && !['-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'].includes(leadCompany);

        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            companyFilter = " AND u.company_name = ?";
        } else {
            // In Admin Portal, show only Core staff, even for Agent leads
            companyFilter = " AND (u.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions') OR u.company_name IS NULL)";
        }

        const finalParams = [];
        if (companyFilter.includes('?')) {
            if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
                finalParams.push(req.user.company_name);
            }
        }
        finalParams.push(...likeParams);

        const [rows] = await pool.execute(
            `SELECT u.id, u.employee_id, u.name, u.email, u.phone, u.role, u.domain, u.user_type, u.company_name
             FROM users u
             LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
             WHERE u.is_active = 1
               ${companyFilter}
               AND (
                 (COALESCE(h.tier, 'STAFF') IN ('STAFF', 'ADMIN') AND (${likeClauses}))
                 OR LOWER(u.role) IN ('main admin', 'md', 'gm', 'super admin')
                 OR LOWER(u.tier) = 'super_admin'
               )
             ORDER BY (LOWER(u.tier) = 'super_admin' OR LOWER(u.role) IN ('main admin', 'md', 'gm', 'super admin')) DESC, u.role DESC, u.name ASC`,
            finalParams
        );
        res.json(rows);
    } catch (err) {
        console.error("Domain Staff Fetch Error:", err);
        res.status(500).json({ error: err.message });
    }
});

app.get('/api/leads/:id/remark-messages', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [leadRows] = await pool.execute(
            'SELECT id, domain, assigned_to, company_name, is_active FROM leads WHERE id = ? AND is_active = 1',
            [Number(id)]
        );
        if (leadRows.length === 0) return res.status(404).json({ msg: 'Lead not found' });
        const lead = leadRows[0];
        if (!canUserAccessLead(lead, req.user)) return res.status(403).json({ msg: 'Access denied' });

        const [rows] = await pool.execute(
            `SELECT
        id,
        ref_id,
        subject,
        description,
        attachment_base64,
        attachment_name,
        attachment_type,
        sent_status,
        created_by,
        created_at,
        updated_at
      FROM lead_remarks_messages
      WHERE lead_id = ?
      ORDER BY created_at DESC`,
            [Number(id)]
        );

        console.log(`Fetched ${rows.length} remark messages for lead ${id}`);
        res.json(rows);
    } catch (err) {
        console.error("Fetch remark messages error:", err);
        res.status(500).json({ error: 'Could not fetch remark messages' });
    }
});
app.get('/api/remark-message-history/:messageId', authenticateToken, async (req, res) => {
    try {
        const { messageId } = req.params;
        const [rows] = await pool.execute(
            `SELECT
        id,
        message_id,
        subject,
        description,
        edited_by,
        edited_at
       FROM lead_remarks_messages_history
       WHERE message_id = ?
       ORDER BY edited_at DESC`,
            [messageId]
        );
        res.json(rows);
    } catch (err) {
        console.error("History fetch error:", err);
        res.status(500).json({ error: "Failed to fetch history" });
    }
});
app.post('/api/leads/:id/remark-messages/send', authenticateToken, async (req, res) => {
    const { id } = req.params;
    let {
        ref_id,
        subject,
        description,
        recipientUserIds = [],
        systemRecipientIds = [],
        agentRecipientIds = [],
        primaryRecipientId = null,
        includeCandidateBCC = false,
        attachment_base64,
        attachment_name,
        attachment_type,
        customToEmails = [],
        customCcEmails = []
    } = req.body;
    // Safe parse recipient ids
    try {
        if (typeof recipientUserIds === "string") recipientUserIds = JSON.parse(recipientUserIds);
        if (typeof systemRecipientIds === "string") systemRecipientIds = JSON.parse(systemRecipientIds);
        if (typeof agentRecipientIds === "string") agentRecipientIds = JSON.parse(agentRecipientIds);
    } catch {
        recipientUserIds = [];
        systemRecipientIds = [];
        agentRecipientIds = [];
    }
    console.log("REMARK SEND REQ BODY:", JSON.stringify(req.body, null, 2));
    if (!subject || !description) {
        return res.status(400).json({
            msg: "subject and description are required"
        });
    }
    try {
        const [leadRows] = await pool.execute(
            `SELECT id, lead_code, student_name, email, domain, assigned_to, company_name, is_active
       FROM leads
       WHERE id = ? AND is_active = 1`,
            [id]
        );
        if (leadRows.length === 0) {
            return res.status(404).json({ msg: "Lead not found" });
        }
        const lead = leadRows[0];
        if (!canUserAccessLead(lead, req.user)) {
            return res.status(403).json({ msg: "Access denied" });
        }
        const resolvedRefId = lead.lead_code || String(lead.id);

        // Normalize internal staff IDs
        const normalizedIds = Array.from(
            new Set(
                (Array.isArray(recipientUserIds) ? recipientUserIds : [])
                    .map((v) => Number(v))
                    .filter((v) => Number.isInteger(v) && v > 0)
            )
        );
        let staffRows = [];
        if (normalizedIds.length > 0) {
            const idPlaceholders = normalizedIds.map(() => "?").join(",");
            const domainAliases = getDomainAliases(lead.domain);
            const domainLikeClauses = domainAliases.map(() => "u.domain LIKE ?").join(" OR ");
            const domainLikeParams = domainAliases.map((a) => `%${a}%`);

            const [rows] = await pool.execute(
                `SELECT u.id, u.name, u.email, u.phone, u.role, u.domain
          FROM users u
          LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
          WHERE u.is_active = 1
          AND (
             (COALESCE(h.tier, 'STAFF') IN ('STAFF', 'ADMIN') AND (${domainLikeClauses}))
             OR LOWER(u.role) IN ('main admin', 'md', 'gm', 'super admin')
             OR LOWER(u.tier) = 'super_admin'
          )
          AND u.id IN (${idPlaceholders})`,
                [...domainLikeParams, ...normalizedIds]
            );
            staffRows = rows;
        }

        // --- NEW: FETCH SYSTEM MANAGED EMAILS ---
        let systemEmails = [];
        let primaryEmail = null;

        const normSystemIds = Array.from(
            new Set(
                (Array.isArray(systemRecipientIds) ? systemRecipientIds : [])
                    .map((v) => Number(v))
                    .filter((v) => Number.isInteger(v) && v > 0)
            )
        );

        // Fetch System Recipients
        const allSystemIds = [...new Set([
            ...normSystemIds,
            ...(primaryRecipientId ? [Number(primaryRecipientId)] : [])
        ])].filter(v => v > 0);

        if (allSystemIds.length > 0) {
            const qm = allSystemIds.map(() => "?").join(",");
            const [sysRows] = await pool.execute(
                `SELECT id, email FROM system_emails WHERE id IN (${qm}) AND is_active = 1`,
                allSystemIds
            );

            sysRows.forEach(r => {
                if (primaryRecipientId && Number(r.id) === Number(primaryRecipientId)) {
                    primaryEmail = r.email;
                } else if (normSystemIds.includes(Number(r.id))) {
                    systemEmails.push(r.email);
                }
            });
        }

        // --- NEW: FETCH AGENT MANAGED EMAILS ---
        let agentEmails = [];
        const normAgentIds = Array.from(
            new Set(
                (Array.isArray(agentRecipientIds) ? agentRecipientIds : [])
                    .map((v) => Number(v))
                    .filter((v) => Number.isInteger(v) && v > 0)
            )
        );

        if (normAgentIds.length > 0) {
            const qm = normAgentIds.map(() => "?").join(",");
            const [agentRows] = await pool.execute(
                `SELECT email FROM agent_mail_management WHERE id IN (${qm}) AND is_active = 1`,
                normAgentIds
            );
            agentEmails = agentRows.map(r => r.email);
        }

        // Fallback: Check if primaryRecipientId belongs to Agent Mail if not found in System Mail
        if (!primaryEmail && primaryRecipientId) {
            const [agentPrimRows] = await pool.execute(
                `SELECT email FROM agent_mail_management WHERE id = ? AND is_active = 1`,
                [primaryRecipientId]
            );
            if (agentPrimRows.length > 0) primaryEmail = agentPrimRows[0].email;
        }

        // --- NEW: HANDLE COMPANY DEFAULTS (REPLACE BLUESTONEOCS FOR AGENTS) ---
        const isAgentComp = lead.company_name && !['-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'].includes(lead.company_name);
        let companyDefaultEmails = [];
        if (isAgentComp) {
            const [compRows] = await pool.execute(
                `SELECT email FROM agent_mail_management WHERE company_name = ? AND is_active = 1`,
                [lead.company_name]
            );
            companyDefaultEmails = compRows.map(r => r.email);
        }

        const staffEmails = staffRows.map(u => u.email).filter(Boolean);
        const candidateEmail = lead.email;

        // Add default assigned staff mail
        let assignedStaffEmail = null;
        if (lead.assigned_to) {
            const [assignedUserRows] = await pool.execute('SELECT email FROM users WHERE id = ? AND is_active = 1', [lead.assigned_to]);
            if (assignedUserRows.length > 0) assignedStaffEmail = assignedUserRows[0].email;
        }

        // Strictly only what is selected or specifically requested (no automatic background CCs)
        const parsedCustomCc = Array.isArray(customCcEmails) ? customCcEmails : [];
        const allCCEmails = [...new Set([
            ...staffEmails,
            ...systemEmails,
            ...agentEmails,
            ...parsedCustomCc,
            assignedStaffEmail
        ])].filter(Boolean);

        let parsedCustomTo = Array.isArray(customToEmails) ? customToEmails : [];
        if (typeof customToEmails === 'string') {
            try { parsedCustomTo = JSON.parse(customToEmails); } catch { parsedCustomTo = customToEmails ? customToEmails.split(',') : []; }
        }

        let defaultTo = ["bluestoneocs@gmail.com", "info@bluestoneocs.com", "bluestonecoimbatore@gmail.com"];
        if (isAgentComp && companyDefaultEmails.length > 0) {
            defaultTo = companyDefaultEmails.filter(e => e.toLowerCase() !== (candidateEmail || '').toLowerCase());
        }

        let finalToArr = [...new Set([...defaultTo, ...parsedCustomTo.map(e => e.trim())])].filter(Boolean);

        // Safety: Candidate email should NEVER be the primary 'To' recipient for internal remarks
        finalToArr = finalToArr.filter(e => e.toLowerCase() !== (candidateEmail || '').toLowerCase());

        let finalTo = finalToArr.join(', ') || "info@bluestoneocs.com";
        const finalCC = allCCEmails.length > 0 ? allCCEmails.join(",") : null;

        // BCC Logic: Always include system default in background, candidate only if enabled
        const bccList = ["info@bluestoneocs.com", "bluestonecoimbatore@gmail.com", "bluestoneocs@gmail.com"];
        if (includeCandidateBCC && candidateEmail) {
            bccList.push(candidateEmail);
        }
        const finalBCC = bccList.join(",");

        console.log("--- REMARK EMAIL DEBUG ---");
        console.log("TO (Dev):", finalTo);
        console.log("CC (Staff/Admins/SuperAdmin):", finalCC);
        console.log("BCC (Candidate):", finalBCC);
        console.log("--------------------------");
        const createdBy = formatWho(req.user);
        // Email HTML
        const html = `
      <div style="font-family: Arial, sans-serif;">
        <p><strong>Candidate:</strong> ${lead.student_name || ""}</p>
         <p><strong>Sent By:</strong> ${createdBy}</p>
        <p><strong>Description:</strong><br/>
        ${description.replace(/\n/g, "<br/>")}
        </p>
      </div>
    `;
        let sentStatus = "FAILED";
        let sentError = "";
        const transporter = createMailTransporter();
        if (!transporter) {
            sentError = "SMTP not configured";
        } else {
            try {
                const mailOptions = {
                    from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                    to: finalTo,
                    cc: finalCC,
                    bcc: finalBCC,
                    subject: `[${lead.lead_code || lead.id}] ${subject}`,
                    html
                };
                // Attach file if present
                if (attachment_base64) {
                    const base64Data = attachment_base64.split("base64,")[1];
                    mailOptions.attachments = [
                        {
                            filename: attachment_name || "attachment",
                            content: base64Data,
                            encoding: "base64",
                            contentType: attachment_type
                        }
                    ];
                }
                await transporter.sendMail(mailOptions);
                sentStatus = "SENT";
            } catch (mailErr) {
                sentError = `Mail send failed: ${mailErr.message}`;
            }
        }
        const nowStr = getISTString();
        // Save to database
        const [insertResult] = await pool.execute(
            `INSERT INTO lead_remarks_messages
      (
        lead_id,
        ref_id,
        subject,
        description,
        recipient_user_ids,
        recipient_emails,
        candidate_email,
        sent_status,
        sent_error,
        created_by,
        attachment_base64,
        attachment_name,
        attachment_type,
        created_at
      )
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
            [
                id,
                resolvedRefId,
                subject,
                description,
                JSON.stringify(normalizedIds),
                staffEmails.join(","),
                candidateEmail,
                sentStatus,
                sentError || null,
                createdBy,
                attachment_base64 || null,
                attachment_name || null,
                attachment_type || null,
                nowStr
            ]
        );
        console.log(`Saved remark message ${insertResult.insertId} for lead ${id}. Status: ${sentStatus}`);

        // Update lead modified time
        await pool.execute('UPDATE leads SET updated_at = ? WHERE id = ?', [nowStr, id]);

        if (sentStatus !== "SENT") {
            return res.status(400).json({
                msg: `Saved, but mail not sent: ${sentError}`
            });
        }

        // ✅ SUPER ADMIN NOTIFICATION
        await createSystemNotification(`Mail sent to Candidate ${resolvedRefId} (Subject: ${subject})|||${description}`, req.user);

        return res.status(201).json({
            msg: "Remark saved and mail sent"
        });
    } catch (err) {
        console.error("Remark send route error:", err);
        return res.status(500).json({
            error: "Failed to save/send remark message",
            details: err.message
        });
    }
});
app.put('/api/leads/:id/remark-messages/:messageId',
    authenticateToken,
    async (req, res) => {
        const { id, messageId } = req.params;
        const {
            ref_id,
            subject,
            description,
            recipientUserIds = [],
            includeCandidateBCC = false,
            attachment_base64,
            attachment_name,
            attachment_type,
            customToEmail = null,
            customCcEmails = [],
            primaryRecipientId = null
        } = req.body;
        if (!subject || !description) {
            return res.status(400).json({ msg: 'subject and description are required' });
        }
        try {
            const [leadRows] = await pool.execute(
                'SELECT id, lead_code, student_name, email, domain, assigned_to, is_active FROM leads WHERE id = ? AND is_active = 1',
                [id]
            );
            if (leadRows.length === 0) return res.status(404).json({ msg: 'Lead not found' });
            const lead = leadRows[0];
            if (!canUserAccessLead(lead, req.user)) return res.status(403).json({ msg: 'Access denied' });
            const resolvedRefId = lead.lead_code || String(lead.id);
            const [msgRows] = await pool.execute(
                'SELECT * FROM lead_remarks_messages WHERE id = ? AND lead_id = ?',
                [messageId, id]
            );
            if (msgRows.length === 0) {
                return res.status(404).json({ msg: 'Message not found' });
            }
            const oldMessage = msgRows[0];
            // 🔹 Save old message into history
            await pool.execute(
                `INSERT INTO lead_remarks_messages_history
    (message_id, lead_id, ref_id, subject, description,
     attachment_base64, attachment_name, attachment_type, edited_by, edited_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                [
                    oldMessage.id,
                    oldMessage.lead_id,
                    oldMessage.ref_id,
                    oldMessage.subject,
                    oldMessage.description,
                    oldMessage.attachment_base64,
                    oldMessage.attachment_name,
                    oldMessage.attachment_type,
                    formatWho(req.user),
                    getISTString()
                ]
            );
            if (msgRows.length === 0) return res.status(404).json({ msg: 'Message not found' });
            const normalizedIds = Array.from(
                new Set((Array.isArray(recipientUserIds) ? recipientUserIds : [])
                    .map((v) => Number(v))
                    .filter((v) => Number.isInteger(v) && v > 0))
            );
            let staffRows = [];
            if (normalizedIds.length > 0) {
                const idPlaceholders = normalizedIds.map(() => "?").join(",");
                const domainAliases = getDomainAliases(lead.domain);
                const domainLikeClauses = domainAliases.map(() => "u.domain LIKE ?").join(" OR ");
                const domainLikeParams = domainAliases.map((a) => `%${a}%`);

                console.log("Searching BCC (Edit) for domain aliases:", domainAliases);

                const [rows] = await pool.execute(
                    `SELECT u.id, u.name, u.email, u.phone, u.role, u.domain
                 FROM users u
                 LEFT JOIN master_user_hierarchy h ON h.role_name = u.role
                 WHERE u.is_active = 1
                   AND (
                     (COALESCE(h.tier, 'STAFF') IN ('STAFF', 'ADMIN') AND (${domainLikeClauses}))
                     OR u.role = 'Super Admin'
                   )
                   AND u.id IN (${idPlaceholders})`,
                    [...domainLikeParams, ...normalizedIds]
                );
                staffRows = rows;
                console.log(`Found ${staffRows.length} valid BCC recipients (Edit)`);
            }
            const staffEmails = staffRows.map(u => u.email).filter(Boolean);
            const candidateEmail = String(lead.email || '').trim();
            const [superAdmins] = await pool.execute(
                `SELECT email FROM users 
                 WHERE (LOWER(role) IN ('main admin', 'md', 'gm', 'super admin') OR LOWER(tier) = 'super_admin') 
                   AND is_active = 1`
            );
            const superAdminEmails = superAdmins.map(u => u.email).filter(Boolean);
            console.log(`Found ${superAdminEmails.length} super admins for CC (Edit):`, superAdminEmails);

            // Add default assigned staff mail
            let assignedStaffEmail = null;
            if (lead.assigned_to) {
                const [assignedUserRows] = await pool.execute('SELECT email FROM users WHERE id = ? AND is_active = 1', [lead.assigned_to]);
                if (assignedUserRows.length > 0) assignedStaffEmail = assignedUserRows[0].email;
            }

            const parsedCustomCc = Array.isArray(customCcEmails) ? customCcEmails : [];
            const allCCEmails = [...new Set([...staffEmails, ...superAdminEmails, ...parsedCustomCc, assignedStaffEmail])].filter(Boolean);

            const finalTo = (primaryRecipientId === 'custom' && customToEmail) ? customToEmail : "info@bluestoneocs.com";
            const finalCC = allCCEmails.length > 0 ? allCCEmails.join(",") : null;

            // Sync BCC Logic with POST: Always include system default in background
            const bccList = ["info@bluestoneocs.com", "bluestonecoimbatore@gmail.com", "bluestoneocs@gmail.com"];
            if (includeCandidateBCC && candidateEmail) {
                bccList.push(candidateEmail);
            }
            const finalBCC = bccList.join(",");

            console.log("--- REMARK EMAIL EDIT DEBUG ---");
            console.log("TO (Dev):", finalTo);
            console.log("CC (Staff/Admins/SuperAdmin):", finalCC);
            console.log("BCC (Candidate):", finalBCC);
            console.log("-------------------------------");
            const createdBy = formatWho(req.user);
            const html = `
            <div style="font-family: Arial, sans-serif; line-height: 1.5;">
                <p><strong>Candidate:</strong> ${String(lead.student_name || '')}</p>  
                 <p><strong>Edited & Sent By:</strong> ${createdBy}</p>         
                <p><strong>Description:</strong><br/>${String(description).replace(/\n/g, '<br/>')}</p>
                
            </div>
        `;
            let sentStatus = 'FAILED';
            let sentError = '';
            const transporter = createMailTransporter();
            if (!transporter) {
                sentError = 'SMTP is not configured on server';
            } else {
                try {
                    const mailOptions = {
                        from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                        to: finalTo,
                        cc: finalCC,
                        bcc: finalBCC,
                        subject: `[${lead.lead_code || lead.id}] ${subject}`,
                        html
                    };
                    if (attachment_base64) {
                        const base64Data = attachment_base64.split("base64,")[1] || attachment_base64;
                        mailOptions.attachments = [{
                            filename: attachment_name || "attachment",
                            content: base64Data,
                            encoding: "base64",
                            contentType: attachment_type
                        }];
                    }
                    await transporter.sendMail(mailOptions);
                    sentStatus = 'SENT';
                } catch (mailErr) {
                    sentError = `Mail send failed: ${mailErr.message}`;
                }
            }
            await pool.execute(
                `UPDATE lead_remarks_messages
             SET ref_id = ?, subject = ?, description = ?, recipient_user_ids = ?, recipient_emails = ?, candidate_email = ?, sent_status = ?, sent_error = ?, created_by = ?, attachment_base64 = ?, attachment_name = ?, attachment_type = ?, updated_at = ?
             WHERE id = ? AND lead_id = ?`,
                [
                    resolvedRefId,
                    String(subject).trim(),
                    String(description).trim(),
                    JSON.stringify(staffRows.map((u) => u.id)),
                    staffEmails.join(','),
                    candidateEmail || null,
                    sentStatus,
                    sentError || null,
                    formatWho(req.user),
                    attachment_base64 || null,
                    attachment_name || null,
                    attachment_type || null,
                    getISTString(),
                    messageId,
                    id
                ]
            );
            if (sentStatus !== 'SENT') {
                return res.status(400).json({ msg: `Updated, but mail not sent: ${sentError}` });
            }
            return res.json({ msg: 'Updated and mail re-sent' });
        } catch (err) {
            console.error('Remark update route error:', err.message);
            return res.status(500).json({ error: 'Failed to update/send remark message', details: err.message });
        }
    });
app.delete('/api/leads/:id/remark-messages/:messageId', authenticateToken, async (req, res) => {
    const { id, messageId } = req.params;
    try {
        const [leadRows] = await pool.execute(
            'SELECT id, domain, assigned_to, is_active FROM leads WHERE id = ? AND is_active = 1',
            [id]
        );
        if (leadRows.length === 0) return res.status(404).json({ msg: 'Lead not found' });
        const lead = leadRows[0];
        if (!canUserAccessLead(lead, req.user)) return res.status(403).json({ msg: 'Access denied' });
        const [result] = await pool.execute(
            'DELETE FROM lead_remarks_messages WHERE id = ? AND lead_id = ?',
            [messageId, id]
        );
        if (result.affectedRows === 0) return res.status(404).json({ msg: 'Message not found' });
        return res.json({ msg: 'Remark message deleted' });
    } catch (err) {
        console.error('Remark delete route error:', err.message);
        return res.status(500).json({ error: 'Failed to delete remark message', details: err.message });
    }
});
app.get('/api/leads/remark-history/:messageId', authenticateToken, async (req, res) => {
    try {
        const { messageId } = req.params;
        const [rows] = await pool.execute(
            `SELECT *
       FROM lead_remarks_messages_history
       WHERE message_id=?
       ORDER BY edited_at DESC`,
            [messageId]
        );
        res.json(rows);
    } catch (err) {
        console.error("History fetch error:", err);
        res.status(500).json({ error: "Failed to fetch history" });
    }
});

// --- HELPER: SYSTEM NOTIFICATIONS ---
async function createSystemNotification(message, reqUser) {
    try {
        const actorName = reqUser?.name || reqUser?.email || "System";
        const nowStr = getISTString();
        const dateStr = nowStr.split(' ')[0]; // YYYY-MM-DD

        // target_domains = 'SUPER_ADMIN_ONLY' ensures regular staff can't see it,
        // but Super Admins bypass the filter entirely in GET /api/notifications.
        const [result] = await pool.execute(
            'INSERT INTO notifications (date, message, flag, is_active, target_domains, target_user_ids, created_by, updated_by, created_at, updated_at) VALUES (?, ?, 1, 1, ?, NULL, ?, ?, ?, ?)',
            [dateStr, message, 'SUPER_ADMIN_ONLY', actorName, actorName, nowStr, nowStr]
        );
        const newId = result.insertId;

        await pool.execute(
            'INSERT INTO notifications_history (notification_id, action_type, new_message, new_date, new_is_active, new_target_domains, new_target_user_ids, changed_by, changed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [newId, 'CREATED', message, dateStr, 1, 'SUPER_ADMIN_ONLY', null, actorName, nowStr]
        );
    } catch (e) {
        console.error("Failed to create automated system notification:", e.message);
    }
}

// --- NOTIFICATION / LIVE FEED ROUTES ---
app.get('/api/notifications/all', authenticateToken, async (req, res) => {
    try {
        // 1. Get all active notifications (Admins see everything)
        const [rows] = await pool.execute(
            `SELECT id, date, message, is_active, flag, target_domains, target_user_ids, created_at, updated_at,
                    COALESCE(NULLIF(updated_by, ''), NULLIF(created_by, ''), 'System') AS updated_by
             FROM notifications
             WHERE flag = 1
             ORDER BY date DESC, id DESC`
        );

        // Ensure we ALWAYS return an array, even if empty
        res.json(Array.isArray(rows) ? rows : []);
    } catch (err) {
        console.error("DATABASE CRASH:", err.message);
        // If the table is missing, this is where it fails
        res.status(500).json({ error: "Table might be missing", details: err.message });
    }
});
// 2. Create Notification (allow multiple notifications for same date)
app.post('/api/notifications', authenticateToken, async (req, res) => {
    const { date, message, target_domains, target_user_ids } = req.body;
    const { role } = req.user;
    const actorName = req.user?.name || req.user?.email || "System";
    // Optional: Restrict to Admins/TLs if you don't want Staff posting broadcasts
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Not authorized to post broadcasts" });
    }
    if (!date || !message) {
        return res.status(400).json({ error: "Date and message are required" });
    }
    try {
        const nowStr = getISTString();
        // Always insert a new row, even if same date exists.
        const [result] = await pool.execute(
            'INSERT INTO notifications (date, message, flag, is_active, target_domains, target_user_ids, created_by, updated_by, created_at, updated_at) VALUES (?, ?, 1, 1, ?, ?, ?, ?, ?, ?)',
            [date, message, target_domains || null, target_user_ids || null, actorName, actorName, nowStr, nowStr]
        );
        const newId = result.insertId;

        await pool.execute(
            'INSERT INTO notifications_history (notification_id, action_type, new_message, new_date, new_is_active, new_target_domains, new_target_user_ids, changed_by, changed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [newId, 'CREATED', message, date, 1, target_domains || null, target_user_ids || null, actorName, nowStr]
        );
        res.status(201).json({ msg: "Notification created successfully" });
    } catch (err) {
        console.error("Create notification error:", err.message);
        res.status(500).json({ error: "Database error" });
    }
});
app.put('/api/notifications/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { message, date, is_active, target_domains, target_user_ids } = req.body;
    const actorName = req.user?.name || req.user?.email || "System";
    try {
        const [oldRows] = await pool.execute('SELECT message, date, is_active, target_domains, target_user_ids FROM notifications WHERE id = ? AND flag = 1', [id]);
        if (oldRows.length === 0) {
            return res.status(404).json({ error: "Notification not found" });
        }
        const oldData = oldRows[0];
        const nowStr = getISTString();
        const [result] = await pool.execute(
            `UPDATE notifications 
       SET message = ?, date = ?, is_active = ?, target_domains = ?, target_user_ids = ?, updated_by = ?, updated_at = ? 
       WHERE id = ?`,
            [message, date, is_active ?? 1, target_domains || null, target_user_ids || null, actorName, nowStr, id]
        );
        await pool.execute(
            'INSERT INTO notifications_history (notification_id, action_type, old_message, new_message, old_date, new_date, old_is_active, new_is_active, old_target_domains, new_target_domains, old_target_user_ids, new_target_user_ids, changed_by, changed_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [id, 'UPDATED', oldData.message, message, oldData.date, date, oldData.is_active, is_active ?? 1, oldData.target_domains, target_domains || null, oldData.target_user_ids, target_user_ids || null, actorName, nowStr]
        );
        res.json({ success: true, message: "Updated successfully" });
    } catch (err) {
        console.error("UPDATE ERROR:", err.message);
        res.status(500).json({ error: "Database error", details: err.message });
    }
});
// 3. Soft Delete (Archive) Notification
app.delete('/api/notifications/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;

    try {
        // We do a soft delete by setting flag to 0
        const [result] = await pool.execute(
            'UPDATE notifications SET flag = 0 WHERE id = ?',
            [id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ msg: "Notification not found" });
        }
        const actorName = req.user?.name || req.user?.email || "System";
        const nowStr = getISTString();
        await pool.execute(
            'INSERT INTO notifications_history (notification_id, action_type, changed_by, changed_at) VALUES (?, ?, ?, ?)',
            [id, 'DELETED', actorName, nowStr]
        );
        res.json({ msg: "Notification archived successfully" });
    } catch (err) {
        console.error("Delete notification error:", err.message);
        res.status(500).json({ error: "Failed to archive notification" });
    }
});
app.patch('/api/notifications/:id/status', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { is_active } = req.body;
    const actorName = req.user?.name || req.user?.email || "System";
    try {
        const [oldRows] = await pool.execute('SELECT is_active FROM notifications WHERE id = ?', [id]);
        if (oldRows.length === 0) return res.status(404).json({ error: "Notification not found" });
        const oldIsActive = oldRows[0].is_active;
        const nowStr = getISTString();
        await pool.execute(
            `UPDATE notifications 
       SET is_active = ?, updated_by = ?, updated_at = ? 
       WHERE id = ?`,
            [is_active, actorName, nowStr, id]
        );
        await pool.execute(
            'INSERT INTO notifications_history (notification_id, action_type, old_is_active, new_is_active, changed_by, changed_at) VALUES (?, ?, ?, ?, ?, ?)',
            [id, 'STATUS_TOGGLED', oldIsActive, is_active, actorName, nowStr]
        );
        res.json({ success: true });
    } catch (err) {
        console.error("TOGGLE ERROR:", err.message);
        res.status(500).json({ error: "Toggle failed" });
    }
});
app.get('/api/notifications/:id/history', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT * FROM notifications_history WHERE notification_id = ? ORDER BY changed_at DESC`,
            [id]
        );
        res.json(rows);
    } catch (err) {
        console.error("HISTORY ERROR:", err.message);
        res.status(500).json({ error: "Failed to fetch history" });
    }
});
// GET: Fetch ALL messages for a specific date, filtered for the current user
app.get('/api/notifications', authenticateToken, async (req, res) => {
    const { date } = req.query;
    const userId = req.user.id;
    const userDomain = String(req.user.domain || '').toLowerCase();
    const isSuper = isSuperAdminUser(req.user);

    try {
        // Fetch all active notifications for the date
        const [rows] = await pool.execute(
            `SELECT id, date, message, is_active, flag, target_domains, target_user_ids, created_at, updated_at,
              COALESCE(NULLIF(updated_by, ''), NULLIF(created_by, ''), 'System') AS updated_by
       FROM notifications 
       WHERE DATE(date) = ? AND is_active = 1 AND flag = 1
       ORDER BY created_at DESC, id DESC`,
            [date]
        );

        if (isSuper) return res.json(rows);

        // Filter based on domain and userId
        const filtered = rows.filter(n => {
            const tDomains = String(n.target_domains || '').toLowerCase().split(',').map(d => d.trim()).filter(Boolean);
            const tUserIds = String(n.target_user_ids || '').split(',').map(u => u.trim()).filter(Boolean);

            // If both are empty, it's global
            if (tDomains.length === 0 && tUserIds.length === 0) return true;

            // Check if user ID is targeted
            if (tUserIds.includes(String(userId))) return true;

            // Check if any of user's domains are targeted
            const myDomains = userDomain.split(',').map(d => d.trim()).filter(Boolean);
            if (myDomains.some(md => tDomains.includes(md))) return true;

            return false;
        });

        res.json(filtered);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
// Toggle active status
app.put("/api/notifications/toggle/:id", async (req, res) => {
    try {
        const { id } = req.params;
        await pool.execute(`
      UPDATE notifications 
      SET is_active = NOT is_active 
      WHERE id = ?
    `, [id]);
        res.json({ message: "Status updated" });
    } catch (err) {
        console.error(err);
        res.status(500).json({ error: err.message });
    }
});
// GET: Fetch notification-date counts for a month (YYYY-MM) for calendar badges
app.get('/api/notifications/calendar', authenticateToken, async (req, res) => {
    const { month } = req.query; // YYYY-MM
    const start = `${month}-01`;
    const end = `${month}-31`;
    const userId = req.user.id;
    const userDomain = String(req.user.domain || '').toLowerCase();
    const isSuper = isSuperAdminUser(req.user);

    try {
        // Fetch all matching notifications for the month
        const [rows] = await pool.execute(
            `SELECT id, DATE(date) as date_key, target_domains, target_user_ids
       FROM notifications
       WHERE date BETWEEN ? AND ?
       AND flag = 1
       AND is_active = 1`,
            [start, end]
        );

        let finalRows = rows;
        if (!isSuper) {
            finalRows = rows.filter(n => {
                const tDomains = String(n.target_domains || '').toLowerCase().split(',').map(d => d.trim()).filter(Boolean);
                const tUserIds = String(n.target_user_ids || '').split(',').map(u => u.trim()).filter(Boolean);
                if (tDomains.length === 0 && tUserIds.length === 0) return true;
                if (tUserIds.includes(String(userId))) return true;
                const myDomains = userDomain.split(',').map(d => d.trim()).filter(Boolean);
                return myDomains.some(md => tDomains.includes(md));
            });
        }

        // Aggregate by date
        const counts = finalRows.reduce((acc, row) => {
            const key = row.date_key;
            acc[key] = (acc[key] || 0) + 1;
            return acc;
        }, {});

        const result = Object.keys(counts).map(date => ({
            date_key: date,
            count: counts[date]
        }));

        res.json(result);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
// Search Leads
app.get('/api/search', authenticateToken, async (req, res) => {
    const { query, page = 1, limit = 10 } = req.query;
    if (!query || query.trim().length < 1) return res.json([]);
    const pageNum = parseInt(page);
    const limitNum = parseInt(limit);
    const offset = (pageNum - 1) * limitNum;

    try {
        const like = `%${query}%`;
        const [rows] = await pool.execute(
            `SELECT * FROM leads WHERE is_active = 1 AND (student_name LIKE ? OR phone LIKE ? OR email LIKE ? OR domain LIKE ? OR intake LIKE ?) ORDER BY created_at DESC LIMIT ? OFFSET ?`,
            [like, like, like, like, like, limitNum, offset]
        );
        const [countResult] = await pool.execute(
            `SELECT COUNT(*) as total FROM leads WHERE is_active = 1 AND (student_name LIKE ? OR phone LIKE ? OR email LIKE ? OR domain LIKE ? OR intake LIKE ?)`,
            [like, like, like, like, like]
        );
        const total = countResult[0].total;

        res.json({
            leads: rows,
            total,
            page: pageNum,
            limit: limitNum,
            totalPages: Math.ceil(total / limitNum)
        });
    } catch (err) {
        res.status(500).json({ error: 'Search failed' });
    }
});
// Get Remarks History for a Lead
app.get('/api/remarks-history/:leadId', authenticateToken, async (req, res) => {
    const { leadId } = req.params;
    try {
        const [history] = await pool.execute(
            `SELECT id, lead_id, old_remarks, new_remarks, changed_by, changed_at 
             FROM remarks_history 
             WHERE lead_id = ? 
             ORDER BY changed_at DESC`,
            [leadId]
        );
        res.json(history);
    } catch (err) {
        console.error('Error fetching remarks history:', err);
        res.status(500).json({ error: 'Failed to fetch remarks history' });
    }
});
app.get('/api/history/leads/:leadId', authenticateToken, async (req, res) => {
    const { leadId } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT id, lead_id, action_type, field_name, old_value, new_value, changed_by, changed_at
             FROM lead_edit_history
             WHERE lead_id = ?
             ORDER BY changed_at DESC, id DESC`,
            [leadId]
        );
        res.json(rows);
    } catch (err) {
        res.status(500).json({ error: 'Failed to fetch lead history' });
    }
});
app.get('/api/history/users/:userId', authenticateToken, async (req, res) => {
    const { userId } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT id, user_id, action_type, field_name, old_value, new_value, changed_by, changed_at
             FROM user_edit_history
             WHERE user_id = ?
             ORDER BY changed_at DESC, id DESC`,
            [userId]
        );
        res.json(rows);
    } catch (err) {
        res.status(500).json({ error: 'Failed to fetch user history' });
    }
});
// Update User Profile
app.put('/api/auth/profile', authenticateToken, async (req, res) => {
    // Destructure exactly what frontend sends
    const { name, phone, avatarBase64, oldPassword, newPassword } = req.body;
    const userId = req.user.id;
    try {
        // 1. Handle Password Change - only if both fields are provided
        if (newPassword && oldPassword) {
            const [rows] = await pool.execute('SELECT password FROM users WHERE id = ? AND is_active = 1', [userId]);
            if (rows.length === 0) return res.status(404).json({ msg: "User not found" });
            const isMatch = await bcrypt.compare(oldPassword, rows[0].password);
            if (!isMatch) return res.status(400).json({ msg: "Old password incorrect" });
            const salt = await bcrypt.hash(newPassword, 10);
            await pool.execute('UPDATE users SET password = ? WHERE id = ? AND is_active = 1', [salt, userId]);
        }
        // 2. Update Profile Info
        // We use COALESCE or simple logic to ensure we don't overwrite with NULL if values are missing
        await pool.execute(
            'UPDATE users SET name = ?, phone = ?, avatar = ? WHERE id = ? AND is_active = 1',
            [name, phone, avatarBase64 || null, userId]
        );
        // 3. Return updated user
        const [updated] = await pool.execute(
            'SELECT id, name, email, phone, role, domain, avatar, can_export_copy, can_edit, show_country_menu FROM users WHERE id = ? AND is_active = 1',
            [userId]
        );

        res.json({ msg: "Profile Updated", user: updated[0] });
    } catch (err) {
        console.error("SERVER ERROR:", err); // Look for this in your terminal!
        res.status(500).json({ error: err.message || "Update failed" });
    }
});
// Verify User Password (for lock screen, etc.)
app.post('/api/auth/verify-password', authenticateToken, async (req, res) => {
    const { password } = req.body;
    const userId = req.user.id;

    if (!password) return res.status(400).json({ msg: "Password required" });
    try {
        const [rows] = await pool.execute('SELECT password FROM users WHERE id = ? AND is_active = 1', [userId]);
        if (rows.length === 0) return res.status(404).json({ msg: "User not found" });
        const isMatch = await bcrypt.compare(password, rows[0].password);
        if (!isMatch) return res.status(401).json({ msg: "Incorrect password" });
        res.json({ msg: "Password verified" });
    } catch (err) {
        console.error("Verify Password Error:", err);
        res.status(500).json({ error: "Verification failed" });
    }
});
// Get User Location Tracking (Super Admin Only)
app.get('/api/location-tracking', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized access" });
    }
    try {
        const [rows] = await pool.execute(`
            SELECT lh.id as log_id, u.id, u.employee_id, u.name, u.email, u.role, u.tier, u.user_type, 
                   lh.ip_address as last_login_ip, lh.location as last_login_location, 
                   lh.login_time as last_active_at,
                   TIMESTAMPDIFF(SECOND, u.last_active_at, CURRENT_TIMESTAMP) AS seconds_since_active 
            FROM login_history lh
            JOIN users u ON lh.user_id = u.id
            ORDER BY lh.login_time DESC
            LIMIT 500
        `);
        res.json(rows);
    } catch (err) {
        console.error("Error fetching location tracking:", err);
        res.status(500).json({ error: "Failed to fetch tracking data" });
    }
});

// --- NEW: STAFF MANAGEMENT ROUTES ---
// 1. Fetch ALL Staff (For the Admin Directory)
// Example fix in server.js
app.get('/api/staff-directory', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute(
            'SELECT id, employee_id, name, role, tier, user_type, company_name, designation, domain, phone, email, avatar, is_active, can_export_copy, can_edit, show_country_menu, last_active_at, last_login_location, TIMESTAMPDIFF(SECOND, last_active_at, CURRENT_TIMESTAMP) AS seconds_since_active FROM users WHERE deleted_at IS NULL ORDER BY id DESC'
        );

        // Core Super Admin: Sees everything
        const userType = req.user.user_type || 'CORE';
        if (isSuperAdminUser(req.user) && userType === 'CORE') {
            return res.json(rows);
        }

        // Agent / Franchise Admin: Only see their own company staff
        if (userType === 'AGENT' || userType === 'FRANCHISE') {
            const filtered = rows.filter(u => u.company_name === req.user.company_name);
            return res.json(filtered);
        }

        // Core Admin: See staff in their domain
        if (!isAdminUser(req.user)) {
            return res.status(403).json({ msg: "Unauthorized to view staff directory." });
        }
        const allowed = getDomainAliases(req.user?.domain || '').map((d) => String(d).trim().toLowerCase());
        const filtered = rows.filter((u) => {
            const staffUserType = u.user_type || 'CORE';
            if (staffUserType !== 'CORE') return false; // Core Admin shouldn't see Agents/Franchises unless explicitly allowed
            const domains = String(u.domain || '')
                .split(',')
                .map((d) => d.trim().toLowerCase())
                .filter(Boolean);
            if (domains.includes('all')) return false;
            const isStaff = deriveTierFromRole(u.role) === 'STAFF';
            if (!isStaff) return false;
            return domains.some((d) => allowed.includes(d));
        });
        res.json(filtered);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
// 1b. Toggle Staff Active Status
app.patch('/api/staff/:id/status', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { is_active } = req.body;

    // Security: Only Admins can toggle status
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized to update staff status." });
    }

    if (parseInt(id, 10) === req.user.id && Number(is_active) === 0) {
        return res.status(400).json({ msg: "You cannot deactivate your own account." });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT name, tier, role, is_active FROM users WHERE id = ? AND deleted_at IS NULL',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "User not found" });
        const before = beforeRows[0];

        // Security: Regular Admins can ONLY manage STAFF tier
        if (!isSuperAdminUser(req.user) && getUserTier(before) !== 'STAFF') {
            return res.status(403).json({ msg: "Admins can only manage Staff tier accounts" });
        }
        await pool.execute(
            'UPDATE users SET is_active = ? WHERE id = ? AND deleted_at IS NULL',
            [
                Number(is_active) ? 1 : 0,
                id
            ]
        );
        const changedBy = req.user?.name || 'System';
        await pool.execute(
            `INSERT INTO user_edit_history (user_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [
                id,
                'USER_STATUS',
                'is_active',
                String(before.is_active),
                String(Number(is_active) ? 1 : 0),
                changedBy,
                getISTString()
            ]
        );
        res.json({ msg: "Staff status updated" });
    } catch (err) {
        res.status(500).json({ error: "Failed to update staff status" });
    }
});

// 1c. Toggle Staff Export & Copy Permission
app.patch('/api/staff/:id/export-copy', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { can_export_copy } = req.body;

    // Security: Only Super Admins can toggle this permission
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can control export/copy permissions." });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT name, can_export_copy FROM users WHERE id = ? AND deleted_at IS NULL',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "User not found" });
        const before = beforeRows[0];

        const newValue = Number(can_export_copy) ? 1 : 0;

        await pool.execute(
            'UPDATE users SET can_export_copy = ? WHERE id = ? AND deleted_at IS NULL',
            [newValue, id]
        );

        const changedBy = req.user?.name || 'System';
        await pool.execute(
            `INSERT INTO user_edit_history (user_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [
                id,
                'USER_PERMISSIONS',
                'can_export_copy',
                String(before.can_export_copy || 0),
                String(newValue),
                changedBy,
                getISTString()
            ]
        );

        res.json({ msg: "Export and copy permission updated successfully", can_export_copy: newValue });
    } catch (err) {
        console.error("Export copy toggle error:", err);
        res.status(500).json({ error: "Failed to update export/copy permission" });
    }
});

// 1d. Toggle Staff Edit Permission
app.patch('/api/staff/:id/edit-permission', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { can_edit } = req.body;

    // Security: Only Super Admins can toggle this permission
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can control edit permissions." });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT name, can_edit FROM users WHERE id = ? AND deleted_at IS NULL',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "User not found" });
        const before = beforeRows[0];

        const newValue = Number(can_edit) ? 1 : 0;

        await pool.execute(
            'UPDATE users SET can_edit = ? WHERE id = ? AND deleted_at IS NULL',
            [newValue, id]
        );

        const changedBy = req.user?.name || 'System';
        await pool.execute(
            `INSERT INTO user_edit_history (user_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [
                id,
                'USER_PERMISSIONS',
                'can_edit',
                String(before.can_edit !== undefined ? before.can_edit : 1),
                String(newValue),
                changedBy,
                getISTString()
            ]
        );

        res.json({ msg: "Edit permission updated successfully", can_edit: newValue });
    } catch (err) {
        console.error("Edit toggle error:", err);
        res.status(500).json({ error: "Failed to update edit permission" });
    }
});

// 1f. Toggle Country Details Sidebar Menu Permission
app.patch('/api/staff/:id/country-menu', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { show_country_menu } = req.body;

    // Security: Only Super Admins can toggle this permission
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can control sidebar menu permissions." });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT name, show_country_menu FROM users WHERE id = ? AND deleted_at IS NULL',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "User not found" });
        const before = beforeRows[0];

        const newValue = Number(show_country_menu) ? 1 : 0;

        await pool.execute(
            'UPDATE users SET show_country_menu = ? WHERE id = ? AND deleted_at IS NULL',
            [newValue, id]
        );

        const changedBy = req.user?.name || 'System';
        await pool.execute(
            `INSERT INTO user_edit_history (user_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
             VALUES (?, ?, ?, ?, ?, ?, ?)`,
            [
                id,
                'USER_PERMISSIONS',
                'show_country_menu',
                String(before.show_country_menu || 0),
                String(newValue),
                changedBy,
                getISTString()
            ]
        );

        res.json({ msg: "Country details menu permission updated successfully", show_country_menu: newValue });
    } catch (err) {
        console.error("Country menu toggle error:", err);
        res.status(500).json({ error: "Failed to update country details menu permission" });
    }
});

// 1e. Log Security Violation and Send Email Notification to bluestoneocs@gmail.com
app.post('/api/security/log-violation', authenticateToken, async (req, res) => {
    const { actionType, details } = req.body;
    const userObj = req.user;

    console.log(`\n🔒 [Security Shield] VIOLATION RECEIVED: "${actionType}"`);
    console.log(`👤 User: ${userObj?.name} (${userObj?.email}) - Role: ${userObj?.role || 'Staff'}`);
    console.log(`📋 Details: ${details || 'None'}`);

    if (!actionType) {
        return res.status(400).json({ error: "Action type is required" });
    }

    try {
        // Formulate Email content nicely using HTML
        const subject = `⚠️ Bluestone 360 Security Alert: ${actionType} Attempted by ${userObj.name}`;
        const htmlContent = `
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div style="background-color: #ef4444; color: #ffffff; padding: 20px; text-align: center;">
                    <h2 style="margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px;">Bluestone 360 Security Alert</h2>
                </div>
                <div style="padding: 24px; color: #334155;">
                    <p style="font-size: 16px; line-height: 1.5; margin-top: 0;">Dear Administrator,</p>
                    <p style="font-size: 14px; line-height: 1.6; color: #475569;">
                        A security access violation was detected and successfully blocked inside Bluestone 360. Details of the incident are below:
                    </p>
                    
                    <div style="background-color: #f8fafc; border-left: 4px solid #ef4444; padding: 16px; margin: 20px 0; border-radius: 4px;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b; width: 140px;">User Name:</td>
                                <td style="padding: 6px 0; color: #475569;">${userObj.name}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">User Email:</td>
                                <td style="padding: 6px 0; color: #475569;">${userObj.email}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">User Role / Tier:</td>
                                <td style="padding: 6px 0; color: #475569;">${userObj.role || 'Staff'} (${userObj.tier || 'STAFF'})</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">Assigned Domains:</td>
                                <td style="padding: 6px 0; color: #475569;">${userObj.domain || 'None'}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">Violation Type:</td>
                                <td style="padding: 6px 0; color: #ef4444; font-weight: bold;">${actionType}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">Description:</td>
                                <td style="padding: 6px 0; color: #475569;">${details || 'No additional details provided.'}</td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 0; font-weight: bold; color: #1e293b;">Time of Event (IST):</td>
                                <td style="padding: 6px 0; color: #475569;">${getISTString()}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <p style="font-size: 12px; color: #94a3b8; margin-top: 30px; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                        This is an automated alert generated by the Bluestone Groups Security Shield. Please review the user permissions under User Management if necessary.
                    </p>
                </div>
            </div>
        `;

        console.log(`✉️ [Security Shield] Dispatching alert email to bluestoneocs@gmail.com...`);
        // Send email to bluestoneocs@gmail.com
        await sendContactEmail('bluestoneocs@gmail.com', subject, htmlContent);
        console.log(`✅ [Security Shield] Alert email successfully sent to bluestoneocs@gmail.com!`);

        res.json({ success: true, msg: "Violation logged and administrator notified." });
    } catch (err) {
        console.error("❌ [Security Shield] Failed to log violation / send email:", err);
        res.status(500).json({ error: "Failed to process security violation report." });
    }
});
// 2. Delete Staff Member
app.delete('/api/staff/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    // Prevent deleting yourself
    if (parseInt(id) === req.user.id) {
        return res.status(400).json({ msg: "You cannot delete your own account." });
    }

    // Security: Only Admins can delete
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized to delete staff." });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT name, tier, role FROM users WHERE id = ? AND deleted_at IS NULL',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "Staff member not found" });
        const before = beforeRows[0];

        // Security: Regular Admins can ONLY delete STAFF tier
        if (!isSuperAdminUser(req.user) && getUserTier(before) !== 'STAFF') {
            return res.status(403).json({ msg: "Admins can only manage Staff tier accounts" });
        }
        const deletedBy = req.user?.id && req.user?.name
            ? `(${req.user.id}) ${req.user.name}`
            : (req.user?.name || 'System');
        const [result] = await pool.execute(
            'UPDATE users SET is_active = 0, deleted_at = NOW(), deleted_by = ? WHERE id = ? AND deleted_at IS NULL',
            [deletedBy, id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ msg: "Staff member not found or already deleted" });
        }
        res.json({ msg: "Staff member soft-deleted successfully" });
    } catch (err) {
        res.status(500).json({ error: "Delete failed" });
    }
});
// 3. Edit Staff Member (Update role/domain/phone)
app.put('/api/staff/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { employee_id, name, email, phone, role, tier, designation, domain, password, user_type, company_name } = req.body;

    // Security: Only Admins can edit
    if (!isAdminUser(req.user)) {
        return res.status(403).json({ msg: "Access Denied" });
    }

    try {
        const [beforeRows] = await pool.execute(
            'SELECT employee_id, name, email, phone, role, tier, designation, domain, user_type, company_name, can_export_copy, can_edit, show_country_menu FROM users WHERE id = ? AND is_active = 1',
            [id]
        );
        if (beforeRows.length === 0) return res.status(404).json({ msg: "User not found" });
        const before = beforeRows[0];

        // Security: Agent / Franchise isolation
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            if (before.company_name !== req.user.company_name) {
                return res.status(403).json({ msg: "Access Denied: You can only manage staff within your company" });
            }
        }

        // Security: Regular Admins can ONLY manage STAFF tier
        if (!isSuperAdminUser(req.user)) {
            if (getUserTier(before) !== 'STAFF') return res.status(403).json({ msg: "Admins can only manage Staff tier accounts" });
            if (tier && tier !== 'STAFF') return res.status(403).json({ msg: "Access Denied: Only Super Admins can elevate account tiers" });
        }
        // 1. Optional: Check if the new email is already used by another user
        if (email) {
            const [existing] = await pool.execute(
                'SELECT id FROM users WHERE email = ? AND id != ?',
                [email, id]
            );
            if (existing.length > 0) {
                return res.status(400).json({ msg: "Email is already in use by another staff member" });
            }
        }
        let query = 'UPDATE users SET employee_id = ?, name = ?, email = ?, phone = ?, role = ?, tier = ?, designation = ?, domain = ?, user_type = ?, company_name = ?, can_export_copy = ?, can_edit = ?, show_country_menu = ?, is_active = ?';
        let params = [
            employee_id || null,
            name,
            email,
            phone,
            role,
            (tier !== undefined && tier !== null) ? tier : before.tier,
            designation || null,
            domain,
            user_type || before.user_type || 'CORE',
            company_name || before.company_name,
            (req.body.can_export_copy !== undefined && req.body.can_export_copy !== null) ? (Number(req.body.can_export_copy) ? 1 : 0) : (before.can_export_copy || 0),
            (req.body.can_edit !== undefined && req.body.can_edit !== null) ? (Number(req.body.can_edit) ? 1 : 0) : (before.can_edit !== undefined ? before.can_edit : 1),
            (req.body.show_country_menu !== undefined && req.body.show_country_menu !== null) ? (Number(req.body.show_country_menu) ? 1 : 0) : (before.show_country_menu || 0),
            (req.body.is_active !== undefined && req.body.is_active !== null) ? (Number(req.body.is_active) ? 1 : 0) : (before.is_active || 1)
        ];
        // 2. Only hash and update password if it's provided
        if (password && password.trim() !== "") {
            const salt = await bcrypt.genSalt(10);
            const hashedPassword = await bcrypt.hash(password, salt);
            query += ', password = ?';
            params.push(hashedPassword);
        }
        query += ' WHERE id = ?';
        params.push(id);
        const [result] = await pool.execute(query, params);
        if (result.affectedRows === 0) {
            return res.status(404).json({ msg: "User not found" });
        }

        const changedBy = req.user?.name || 'System';
        const nowStr = getISTString();
        const historyRows = [];

        // Log changes
        if ((before.employee_id || '') !== (employee_id || '')) historyRows.push([id, 'USER_EDIT', 'employee_id', before.employee_id || '', employee_id || '', changedBy, nowStr]);
        if ((before.name || '') !== (name || '')) historyRows.push([id, 'USER_EDIT', 'name', before.name || '', name || '', changedBy, nowStr]);
        if ((before.email || '') !== (email || '')) historyRows.push([id, 'USER_EDIT', 'email', before.email || '', email || '', changedBy, nowStr]);
        if ((before.phone || '') !== (phone || '')) historyRows.push([id, 'USER_EDIT', 'phone', before.phone || '', phone || '', changedBy, nowStr]);
        if ((before.role || '') !== (role || '')) historyRows.push([id, 'USER_EDIT', 'role', before.role || '', role || '', changedBy, nowStr]);
        if ((before.tier || '') !== (tier || '')) historyRows.push([id, 'USER_EDIT', 'tier', before.tier || '', tier || '', changedBy, nowStr]);
        if ((before.designation || '') !== (designation || '')) historyRows.push([id, 'USER_EDIT', 'designation', before.designation || '', designation || '', changedBy, nowStr]);
        if ((before.domain || '') !== (domain || '')) historyRows.push([id, 'USER_EDIT', 'domain', before.domain || '', domain || '', changedBy, nowStr]);
        if (Number(before.can_export_copy || 0) !== Number(req.body.can_export_copy !== undefined ? req.body.can_export_copy : before.can_export_copy)) {
            historyRows.push([id, 'USER_EDIT', 'can_export_copy', String(before.can_export_copy || 0), String(req.body.can_export_copy || 0), changedBy, nowStr]);
        }
        if (Number(before.show_country_menu || 0) !== Number(req.body.show_country_menu !== undefined ? req.body.show_country_menu : before.show_country_menu)) {
            historyRows.push([id, 'USER_EDIT', 'show_country_menu', String(before.show_country_menu || 0), String(req.body.show_country_menu || 0), changedBy, nowStr]);
        }
        if (Number(before.can_edit !== undefined ? before.can_edit : 1) !== Number(req.body.can_edit !== undefined ? req.body.can_edit : (before.can_edit !== undefined ? before.can_edit : 1))) {
            historyRows.push([id, 'USER_EDIT', 'can_edit', String(before.can_edit !== undefined ? before.can_edit : 1), String(req.body.can_edit !== undefined ? req.body.can_edit : 1), changedBy, nowStr]);
        }
        if (Number(before.is_active) !== Number(req.body.is_active !== undefined ? req.body.is_active : before.is_active)) {
            historyRows.push([id, 'USER_EDIT', 'is_active', String(before.is_active), String(req.body.is_active || 0), changedBy, nowStr]);
        }

        if (password && password.trim() !== "") historyRows.push([id, 'PASSWORD_RESET', 'password', '********', '********', changedBy, nowStr]);

        for (const h of historyRows) {
            await pool.execute(
                `INSERT INTO user_edit_history (user_id, action_type, field_name, old_value, new_value, changed_by, changed_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?)`,
                h
            );
        }

        // Return the updated data to verify
        const [rows] = await pool.execute('SELECT id, name, email, phone, role, tier, designation, domain FROM users WHERE id = ?', [id]);

        res.json({ msg: "Staff updated successfully", user: rows[0] });
    } catch (err) {
        console.error("Update Error:", err);
        res.status(500).json({ error: "Database update failed: " + err.message });
    }
});

// 4. GET Staff Personal Details
app.get('/api/staff/:id/personal-details', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT *, 
             DATE_FORMAT(dob, '%Y-%m-%d') as dob, 
             DATE_FORMAT(doj, '%Y-%m-%d') as doj 
             FROM staff_personal_details WHERE user_id = ?`,
            [id]
        );
        if (rows.length === 0) {
            return res.status(200).json({}); // No details yet
        }
        res.json(rows[0]);
    } catch (err) {
        console.error("Fetch personal details error:", err);
        res.status(500).json({ error: "Failed to fetch personal details" });
    }
});

// 5. POST/UPDATE Staff Personal Details
app.post('/api/staff/:id/personal-details', authenticateToken, (req, res) => {
    // Wrap multer in a custom handler to catch errors gracefully
    const cpUpload = uploadStaffDocs.any(); // Use any() to allow dynamic document names
    cpUpload(req, res, async function (err) {
        if (err instanceof multer.MulterError) {
            return res.status(400).json({ msg: `Upload error: ${err.message}` });
        } else if (err) {
            return res.status(400).json({ msg: `Upload error: ${err.message}` });
        }

        const { id } = req.params;
        const { aadhar_name, personal_email, phone, dob, doj, address, has_experience, blood_group, emergency_contact, department } = req.body;

        // Security: Only Admins can edit
        if (!isAdminUser(req.user)) {
            return res.status(403).json({ msg: "Access Denied" });
        }

        try {
            const [userRows] = await pool.execute('SELECT id FROM users WHERE id = ?', [id]);
            if (userRows.length === 0) return res.status(404).json({ msg: "User not found" });

            const [existing] = await pool.execute('SELECT * FROM staff_personal_details WHERE user_id = ?', [id]);

            // Helper to find file path by fieldname
            const getFilePath = (fieldName) => {
                const file = req.files && req.files.find(f => f.fieldname === fieldName);
                return file ? file.path : null;
            };

            let resume_path = getFilePath('resume') || existing[0]?.resume_path || null;
            let aadhar_path = getFilePath('aadhar') || existing[0]?.aadhar_path || null;
            let pan_path = getFilePath('pan') || existing[0]?.pan_path || null;
            let degree_path = getFilePath('degree') || existing[0]?.degree_path || null;
            let experience_path = getFilePath('experience_cert') || existing[0]?.experience_path || null;
            let self_attestation_path = getFilePath('self_attestation') || existing[0]?.self_attestation_path || null;
            let bank_details_path = getFilePath('bank_details') || existing[0]?.bank_details_path || null;
            let offer_letter_path = getFilePath('offer_letter') || existing[0]?.offer_letter_path || null;
            let payslip_path = getFilePath('payslip') || existing[0]?.payslip_path || null;

            // Handle dynamic other docs
            let otherDocsArray = [];
            if (existing.length > 0 && existing[0].other_doc_path) {
                try {
                    otherDocsArray = JSON.parse(existing[0].other_doc_path);
                } catch (e) {
                    // Ignore if not JSON
                }
            }

            // Find all newly uploaded files that start with 'other_doc_'
            const newOtherDocs = req.files ? req.files.filter(f => f.fieldname.startsWith('other_doc_')) : [];
            newOtherDocs.forEach(file => {
                // The fieldname might be "other_doc_SomeName"
                const docName = file.fieldname.replace('other_doc_', '');
                otherDocsArray.push({
                    name: docName,
                    path: file.path
                });
            });

            // Also check if any existing other docs were requested to be deleted (handled optionally via req.body.delete_other_docs)
            if (req.body.delete_other_docs) {
                const toDelete = JSON.parse(req.body.delete_other_docs);
                otherDocsArray = otherDocsArray.filter(doc => !toDelete.includes(doc.path));
            }

            let other_doc_path = JSON.stringify(otherDocsArray);

            // Required check for new records
            if (existing.length === 0) {
                if (!resume_path || !aadhar_path || !pan_path || !degree_path) {
                    return res.status(400).json({ msg: "Missing mandatory documents (Resume, Aadhar, PAN, Degree)" });
                }
                if (String(has_experience) === '1' || String(has_experience) === 'true') {
                    if (!experience_path) {
                        return res.status(400).json({ msg: "Experience certificate is required since experience is marked as yes." });
                    }
                }
            }

            const expVal = (String(has_experience) === '1' || String(has_experience) === 'true') ? 1 : 0;

            if (existing.length === 0) {
                await pool.execute(
                    `INSERT INTO staff_personal_details (
                        user_id, aadhar_name, personal_email, phone, dob, doj, address, has_experience,
                        blood_group, emergency_contact, department,
                        resume_path, aadhar_path, pan_path, degree_path, experience_path,
                        self_attestation_path, bank_details_path, offer_letter_path, payslip_path,
                        other_doc_path
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                    [
                        id, aadhar_name, personal_email, phone, dob, doj, address, expVal,
                        blood_group || null, emergency_contact || null, department || null,
                        resume_path, aadhar_path, pan_path, degree_path, experience_path,
                        self_attestation_path, bank_details_path, offer_letter_path, payslip_path,
                        other_doc_path
                    ]
                );
            } else {
                await pool.execute(
                    `UPDATE staff_personal_details SET
                        aadhar_name = ?, personal_email = ?, phone = ?, dob = ?, doj = ?, address = ?, has_experience = ?,
                        blood_group = ?, emergency_contact = ?, department = ?,
                        resume_path = ?, aadhar_path = ?, pan_path = ?, degree_path = ?, experience_path = ?,
                        self_attestation_path = ?, bank_details_path = ?, offer_letter_path = ?, payslip_path = ?,
                        other_doc_path = ?
                    WHERE user_id = ?`,
                    [
                        aadhar_name, personal_email, phone, dob, doj, address, expVal,
                        blood_group || null, emergency_contact || null, department || null,
                        resume_path, aadhar_path, pan_path, degree_path, experience_path,
                        self_attestation_path, bank_details_path, offer_letter_path, payslip_path,
                        other_doc_path, id
                    ]
                );
            }

            res.json({ msg: "Personal details updated successfully" });
        } catch (err) {
            console.error("Save personal details error:", err);
            res.status(500).json({ error: "Failed to save personal details" });
        }
    });
});

// ==========================================
// STANDALONE STAFF RECORDS (HR Management)
// ==========================================

// GET all staff records
app.get('/api/staff-records', authenticateToken, async (req, res) => {
    if (getUserTier(req.user) !== 'SUPER_ADMIN') return res.status(403).json({ msg: 'Access Denied' });
    try {
        const [rows] = await pool.execute(
            `SELECT id, employee_id, name, personal_email, phone,
             DATE_FORMAT(dob, '%Y-%m-%d') as dob,
             DATE_FORMAT(doj, '%Y-%m-%d') as doj,
             blood_group, department, designation, emergency_contact, address, has_experience,
             aadhar_path, pan_path, self_attestation_path, degree_path, bank_details_path,
             resume_path, offer_letter_path, experience_cert_path, payslip_path, other_doc_path,
             created_at, updated_at
             FROM staff_records ORDER BY name ASC`
        );
        res.json(rows);
    } catch (err) {
        console.error('Fetch staff records error:', err);
        res.status(500).json({ error: 'Failed to fetch staff records' });
    }
});

// POST create new staff record
app.post('/api/staff-records', authenticateToken, (req, res) => {
    const cpUpload = uploadStaffDocs.any();
    cpUpload(req, res, async (err) => {
        if (err) return res.status(400).json({ msg: `Upload error: ${err.message}` });
        if (getUserTier(req.user) !== 'SUPER_ADMIN') return res.status(403).json({ msg: 'Access Denied' });

        const {
            employee_id, name, personal_email, phone, dob, doj,
            blood_group, department, designation, emergency_contact, address, has_experience
        } = req.body;

        if (!name || !name.trim()) return res.status(400).json({ msg: 'Full name is required' });
        if (!phone || !phone.trim()) return res.status(400).json({ msg: 'Phone number is required' });

        try {
            const getFile = (fieldName) => {
                const f = req.files && req.files.find(f => f.fieldname === fieldName);
                return f ? f.path.replace(/\\/g, '/') : null;
            };

            const otherDocs = (req.files || [])
                .filter(f => f.fieldname.startsWith('other_doc_'))
                .map(f => ({ name: f.fieldname.replace('other_doc_', ''), path: f.path.replace(/\\/g, '/') }));

            const expVal = (String(has_experience) === '1' || has_experience === true || has_experience === 'true') ? 1 : 0;

            const [result] = await pool.execute(
                `INSERT INTO staff_records (
                    employee_id, name, personal_email, phone, dob, doj,
                    blood_group, department, designation, emergency_contact, address, has_experience,
                    aadhar_path, pan_path, self_attestation_path, degree_path, bank_details_path,
                    resume_path, offer_letter_path, experience_cert_path, payslip_path, other_doc_path
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                [
                    employee_id || null, name.trim(), personal_email || null, phone.trim(),
                    dob || null, doj || null, blood_group || null, department || null,
                    designation || null, emergency_contact || null, address || null, expVal,
                    getFile('aadhar'), getFile('pan'), getFile('self_attestation'), getFile('degree'),
                    getFile('bank_details'), getFile('resume'), getFile('offer_letter'),
                    getFile('experience_cert'), getFile('payslip'),
                    otherDocs.length > 0 ? JSON.stringify(otherDocs) : null
                ]
            );

            // Log History
            await pool.execute(
                `INSERT INTO staff_records_history (staff_record_id, action_type, action_details, performed_by) VALUES (?, ?, ?, ?)`,
                [result.insertId, 'CREATED', 'Staff record created.', req.user.email || 'Admin']
            );

            res.status(201).json({ msg: 'Staff record created', id: result.insertId });
        } catch (err) {
            console.error('Create staff record error:', err);
            res.status(500).json({ error: 'Failed to create staff record' });
        }
    });
});

// PUT update staff record
app.put('/api/staff-records/:id', authenticateToken, (req, res) => {
    const cpUpload = uploadStaffDocs.any();
    cpUpload(req, res, async (err) => {
        if (err) return res.status(400).json({ msg: `Upload error: ${err.message}` });
        if (getUserTier(req.user) !== 'SUPER_ADMIN') return res.status(403).json({ msg: 'Access Denied' });

        const { id } = req.params;
        const {
            employee_id, name, personal_email, phone, dob, doj,
            blood_group, department, designation, emergency_contact, address, has_experience,
            delete_other_docs
        } = req.body;

        if (!name || !name.trim()) return res.status(400).json({ msg: 'Full name is required' });

        try {
            const [existing] = await pool.execute('SELECT * FROM staff_records WHERE id = ?', [id]);
            if (existing.length === 0) return res.status(404).json({ msg: 'Staff record not found' });
            const ex = existing[0];

            const getFile = (fieldName) => {
                const f = req.files && req.files.find(f => f.fieldname === fieldName);
                return f ? f.path.replace(/\\/g, '/') : null;
            };

            const expVal = (String(has_experience) === '1' || has_experience === true || has_experience === 'true') ? 1 : 0;

            // Handle other docs: keep existing, append new, remove deleted
            let otherDocsArray = [];
            if (ex.other_doc_path) {
                try { otherDocsArray = JSON.parse(ex.other_doc_path); } catch { }
            }
            if (delete_other_docs) {
                const toDelete = JSON.parse(delete_other_docs);
                otherDocsArray = otherDocsArray.filter(d => !toDelete.includes(d.path));
            }
            const newOtherDocs = (req.files || [])
                .filter(f => f.fieldname.startsWith('other_doc_'))
                .map(f => ({ name: f.fieldname.replace('other_doc_', ''), path: f.path.replace(/\\/g, '/') }));
            otherDocsArray.push(...newOtherDocs);

            // Build a list of changes
            let changes = [];
            if (employee_id && employee_id !== ex.employee_id) changes.push(`Emp ID changed from "${ex.employee_id || 'N/A'}" to "${employee_id}"`);
            if (name && name.trim() !== ex.name) changes.push(`Name changed from "${ex.name || 'N/A'}" to "${name.trim()}"`);
            if (personal_email && personal_email !== ex.personal_email) changes.push(`Email changed from "${ex.personal_email || 'N/A'}" to "${personal_email}"`);
            if (phone && phone.trim() !== ex.phone) changes.push(`Phone changed from "${ex.phone || 'N/A'}" to "${phone.trim()}"`);

            const formatD = d => d ? new Date(d).toISOString().split('T')[0] : null;
            if (dob && dob !== formatD(ex.dob)) changes.push(`DOB changed from "${formatD(ex.dob) || 'N/A'}" to "${dob}"`);
            if (doj && doj !== formatD(ex.doj)) changes.push(`DOJ changed from "${formatD(ex.doj) || 'N/A'}" to "${doj}"`);
            if (blood_group && blood_group !== ex.blood_group) changes.push(`Blood Group changed from "${ex.blood_group || 'N/A'}" to "${blood_group}"`);
            if (department && department !== ex.department) changes.push(`Department changed from "${ex.department || 'N/A'}" to "${department}"`);
            if (designation && designation !== ex.designation) changes.push(`Designation changed from "${ex.designation || 'N/A'}" to "${designation}"`);
            if (emergency_contact && emergency_contact !== ex.emergency_contact) changes.push(`Emergency Contact changed from "${ex.emergency_contact || 'N/A'}" to "${emergency_contact}"`);
            if (address && address !== ex.address) changes.push(`Address changed from "${ex.address || 'N/A'}" to "${address}"`);
            if (expVal !== ex.has_experience) changes.push(`Experience set from "${ex.has_experience ? 'Yes' : 'No'}" to "${expVal ? 'Yes' : 'No'}"`);

            const checkF = (k, label) => { if (getFile(k)) changes.push(`New ${label} uploaded`); };
            checkF('aadhar', 'Aadhar');
            checkF('pan', 'PAN');
            checkF('self_attestation', 'Self Attestation');
            checkF('degree', 'Degree');
            checkF('bank_details', 'Bank Details');
            checkF('resume', 'Resume');
            checkF('offer_letter', 'Offer Letter');
            checkF('experience_cert', 'Experience Cert');
            checkF('payslip', 'Payslip');

            if (newOtherDocs.length > 0) changes.push(`${newOtherDocs.length} extra doc(s) added`);
            if (delete_other_docs && JSON.parse(delete_other_docs).length > 0) changes.push(`${JSON.parse(delete_other_docs).length} extra doc(s) removed`);

            let actionDetailStr = changes.length > 0 ? changes.join(' | ') : 'Staff record updated without data changes.';

            await pool.execute(
                `UPDATE staff_records SET
                    employee_id = ?, name = ?, personal_email = ?, phone = ?, dob = ?, doj = ?,
                    blood_group = ?, department = ?, designation = ?, emergency_contact = ?, address = ?, has_experience = ?,
                    aadhar_path = ?, pan_path = ?, self_attestation_path = ?, degree_path = ?,
                    bank_details_path = ?, resume_path = ?, offer_letter_path = ?, experience_cert_path = ?,
                    payslip_path = ?, other_doc_path = ?
                WHERE id = ?`,
                [
                    employee_id || null, name.trim(), personal_email || null, phone.trim() || ex.phone,
                    dob || null, doj || null, blood_group || null, department || null,
                    designation || null, emergency_contact || null, address || null, expVal,
                    getFile('aadhar') || ex.aadhar_path,
                    getFile('pan') || ex.pan_path,
                    getFile('self_attestation') || ex.self_attestation_path,
                    getFile('degree') || ex.degree_path,
                    getFile('bank_details') || ex.bank_details_path,
                    getFile('resume') || ex.resume_path,
                    getFile('offer_letter') || ex.offer_letter_path,
                    getFile('experience_cert') || ex.experience_cert_path,
                    getFile('payslip') || ex.payslip_path,
                    otherDocsArray.length > 0 ? JSON.stringify(otherDocsArray) : null,
                    id
                ]
            );

            // Log History
            await pool.execute(
                `INSERT INTO staff_records_history (staff_record_id, action_type, action_details, performed_by) VALUES (?, ?, ?, ?)`,
                [id, 'UPDATED', actionDetailStr, req.user.email || 'Admin']
            );

            res.json({ msg: 'Staff record updated' });
        } catch (err) {
            console.error('Update staff record error:', err);
            res.status(500).json({ error: 'Failed to update staff record' });
        }
    });
});

// DELETE staff record
app.delete('/api/staff-records/:id', authenticateToken, async (req, res) => {
    if (getUserTier(req.user) !== 'SUPER_ADMIN') return res.status(403).json({ msg: 'Access Denied' });
    const { id } = req.params;
    try {
        const [existing] = await pool.execute('SELECT name FROM staff_records WHERE id = ?', [id]);
        if (existing.length === 0) return res.status(404).json({ msg: 'Record not found' });

        const [result] = await pool.execute('DELETE FROM staff_records WHERE id = ?', [id]);

        // Log History (though if CASCADE is on, it might delete the history, but let's record it if CASCADE is disabled or for reference)
        // Actually, if ON DELETE CASCADE is set, inserting after won't work if FK constraints are enforced. 
        // We will just return. The history is deleted.

        res.json({ msg: 'Staff record deleted' });
    } catch (err) {
        console.error('Delete staff record error:', err);
        res.status(500).json({ error: 'Failed to delete staff record' });
    }
});

// GET staff record history
app.get('/api/staff-records/:id/history', authenticateToken, async (req, res) => {
    if (getUserTier(req.user) !== 'SUPER_ADMIN') return res.status(403).json({ msg: 'Access Denied' });
    const { id } = req.params;
    try {
        const [rows] = await pool.execute(
            `SELECT id, action_type, action_details, performed_by, performed_at 
             FROM staff_records_history 
             WHERE staff_record_id = ? 
             ORDER BY performed_at DESC`,
            [id]
        );
        res.json(rows);
    } catch (err) {
        console.error('Fetch staff history error:', err);
        res.status(500).json({ error: 'Failed to fetch staff history' });
    }
});

// ==========================================
// MEDIA GALLERY ROUTES
// ==========================================

// GET all media
app.get('/api/media', authenticateToken, async (req, res) => {
    if (getUserTier(req.user) !== 'SUPER_ADMIN' && getUserTier(req.user) !== 'ADMIN') {
        return res.status(403).json({ msg: 'Access Denied' });
    }
    const { domain, category, period } = req.query;

    let query = 'SELECT * FROM media_gallery WHERE 1=1';
    let params = [];

    if (domain && domain !== 'all') {
        query += ' AND domain_name = ?';
        params.push(domain);
    }
    if (category && category !== 'all') {
        query += ' AND category = ?';
        params.push(category);
    }
    if (period && period !== 'all') {
        query += ' AND period_year = ?';
        params.push(period);
    }

    query += ' ORDER BY upload_date DESC';

    try {
        const [rows] = await pool.execute(query, params);
        res.json(rows);
    } catch (err) {
        console.error('Fetch media error:', err);
        res.status(500).json({ error: 'Failed to fetch media' });
    }
});

// POST create media
app.post('/api/media', uploadMedia.single('file'), async (req, res) => {
    if (!req.user && req.headers.authorization) {
        try {
            const token = req.headers.authorization.split(' ')[1];
            req.user = jwt.verify(token, JWT_SECRET);
        } catch (err) {
            req.user = null;
        }
    }

    if (req.user && getUserTier(req.user) !== 'SUPER_ADMIN' && getUserTier(req.user) !== 'ADMIN') {
        return res.status(403).json({ msg: 'Access Denied' });
    }

    if (!req.file) return res.status(400).json({ msg: 'No file uploaded' });

    const { domain, category, period, customPeriod, month, title } = req.body;

    if (!domain) return res.status(400).json({ msg: 'Domain is required' });
    if (!category) return res.status(400).json({ msg: 'Category is required' });

    const fileCategory = getMediaFileCategory(req.file.mimetype);
    const sizeLimits = { image: 5, video: 200, document: 20 }; // in MB
    const maxMB = sizeLimits[fileCategory];
    const resolvedPeriod = period === 'custom' ? (customPeriod || null) : (period || null);

    try {
        await ensureMediaGalleryColumns();

        let uploadedFile = req.file;
        if (fileCategory === 'image') {
            try {
                const compressed = await compressImageIfNeeded(req.file.path, req.file.originalname, req.file.mimetype);
                uploadedFile = {
                    ...req.file,
                    path: compressed.path,
                    filename: compressed.filename,
                    size: compressed.size
                };
            } catch (compressionErr) {
                console.warn('Image compression skipped:', compressionErr.message);
                uploadedFile = req.file;
            }
        }

        if (uploadedFile.size > maxMB * 1024 * 1024) {
            fs.unlink(uploadedFile.path, () => { });
            return res.status(400).json({ msg: `File too large. Max size for ${fileCategory}s is ${maxMB}MB.` });
        }

        const filePath = uploadedFile.path.replace(/\\/g, '/');
        const [result] = await pool.execute(
            'INSERT INTO media_gallery (domain_name, category, period_year, month, file_path, title) VALUES (?, ?, ?, ?, ?, ?)',
            [domain, category, resolvedPeriod, month || null, filePath, title || null]
        );
        res.status(201).json({ msg: 'Media uploaded successfully', id: result.insertId });
    } catch (err) {
        console.error('Upload media error:', err);
        res.status(500).json({ error: err.message || 'Failed to upload media' });
    }
});

app.put('/api/media/:id', async (req, res) => {
    if (!req.user && req.headers.authorization) {
        try {
            const token = req.headers.authorization.split(' ')[1];
            req.user = jwt.verify(token, JWT_SECRET);
        } catch (err) {
            req.user = null;
        }
    }

    if (req.user && getUserTier(req.user) !== 'SUPER_ADMIN' && getUserTier(req.user) !== 'ADMIN') {
        return res.status(403).json({ msg: 'Access Denied' });
    }

    const { id } = req.params;
    const { domain, category, period, customPeriod, month, title } = req.body;
    const resolvedPeriod = period === 'custom' ? (customPeriod || null) : (period || null);

    try {
        await ensureMediaGalleryColumns();
        await pool.execute(
            'UPDATE media_gallery SET domain_name = ?, category = ?, period_year = ?, month = ?, title = ? WHERE id = ?',
            [domain, category, resolvedPeriod, month || null, title || null, id]
        );
        res.json({ msg: 'Media updated successfully' });
    } catch (err) {
        console.error('Update media error:', err);
        res.status(500).json({ error: 'Failed to update media' });
    }
});

// DELETE media
app.delete('/api/media/:id', async (req, res) => {
    if (!req.user && req.headers.authorization) {
        try {
            const token = req.headers.authorization.split(' ')[1];
            req.user = jwt.verify(token, JWT_SECRET);
        } catch (err) {
            req.user = null;
        }
    }

    if (req.user && getUserTier(req.user) !== 'SUPER_ADMIN' && getUserTier(req.user) !== 'ADMIN') {
        return res.status(403).json({ msg: 'Access Denied' });
    }

    const { id } = req.params;

    try {
        const [existing] = await pool.execute('SELECT file_path FROM media_gallery WHERE id = ?', [id]);
        if (existing.length === 0) return res.status(404).json({ msg: 'Media not found' });

        await pool.execute('DELETE FROM media_gallery WHERE id = ?', [id]);

        // Optionally, delete file from filesystem
        const fs = require('fs');
        const path = require('path');
        const fullPath = path.join(__dirname, existing[0].file_path);
        if (fs.existsSync(fullPath)) {
            fs.unlinkSync(fullPath);
        }

        res.json({ msg: 'Media deleted successfully' });
    } catch (err) {
        console.error('Delete media error:', err);
        res.status(500).json({ error: 'Failed to delete media' });
    }
});

// ==========================================
// 1. DOMAIN ROUTES
// ==========================================
// Get all domains
app.get('/api/master/sources', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.query(`SELECT DISTINCT source FROM leads WHERE source IS NOT NULL AND source != '' ORDER BY source ASC`);
        const sources = rows.map(r => r.source);
        res.json(sources);
    } catch (err) {
        console.error('Error fetching sources:', err);
        res.status(500).json({ error: 'Failed to fetch sources' });
    }
});

app.get('/api/master/domains', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM master_domains ORDER BY COALESCE(sequence, 999999), name ASC');
        res.json(rows);
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// Create new domain
app.post('/api/master/domains', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) return res.status(403).json({ msg: "Super admin only" });
    const { name, icon_type, icon_name, logo_url, submenus, sequence } = req.body;
    const safeSequence = Number.isFinite(Number(sequence)) ? Number(sequence) : null;
    const safeIconType = ['default', 'react_icon', 'logo'].includes(icon_type) ? icon_type : 'default';
    const safeIconName = safeIconType === 'react_icon' ? (icon_name || null) : null;
    const safeLogoUrl = safeIconType === 'logo' ? (logo_url || null) : null;
    const safeSubmenus = JSON.stringify(
        Array.isArray(submenus)
            ? submenus
                .filter((x) => x && typeof x.name === 'string' && typeof x.path === 'string')
                .map((x) => ({ name: x.name.trim(), path: x.path.trim() }))
                .filter((x) => x.name && x.path)
            : []
    );
    try {
        await pool.execute(
            'INSERT INTO master_domains (name, sequence, icon_type, icon_name, logo_url, submenus) VALUES (?, ?, ?, ?, ?, ?)',
            [name, safeSequence, safeIconType, safeIconName, safeLogoUrl, safeSubmenus]
        );
        res.json({ msg: "Domain created" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// Edit Domain
app.put('/api/master/domains/:id', authenticateToken, async (req, res) => {
    const { name, icon_type, icon_name, logo_url, submenus, sequence } = req.body;
    const safeSequence = Number.isFinite(Number(sequence)) ? Number(sequence) : null;
    const safeIconType = ['default', 'react_icon', 'logo'].includes(icon_type) ? icon_type : null;
    const safeIconName = safeIconType === 'react_icon' ? (icon_name || null) : null;
    const safeLogoUrl = safeIconType === 'logo' ? (logo_url || null) : null;
    const safeSubmenus = Array.isArray(submenus)
        ? JSON.stringify(
            submenus
                .filter((x) => x && typeof x.name === 'string' && typeof x.path === 'string')
                .map((x) => ({ name: x.name.trim(), path: x.path.trim() }))
                .filter((x) => x.name && x.path)
        )
        : null;
    try {
        if (safeIconType) {
            if (Array.isArray(submenus)) {
                await pool.execute(
                    'UPDATE master_domains SET name = ?, sequence = ?, icon_type = ?, icon_name = ?, logo_url = ?, submenus = ? WHERE id = ?',
                    [name, safeSequence, safeIconType, safeIconName, safeLogoUrl, safeSubmenus, req.params.id]
                );
            } else {
                await pool.execute(
                    'UPDATE master_domains SET name = ?, sequence = ?, icon_type = ?, icon_name = ?, logo_url = ? WHERE id = ?',
                    [name, safeSequence, safeIconType, safeIconName, safeLogoUrl, req.params.id]
                );
            }
        } else {
            await pool.execute('UPDATE master_domains SET name = ?, sequence = ? WHERE id = ?', [name, safeSequence, req.params.id]);
        }
        res.json({ msg: "Domain Updated" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// Delete Domain
app.delete('/api/master/domains/:id', authenticateToken, async (req, res) => {
    try {
        await pool.execute('DELETE FROM master_domains WHERE id = ?', [req.params.id]);
        res.json({ msg: "Domain and all associated data deleted" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// ==========================================
// 2. CATEGORY ROUTES (Step 2)
// ==========================================
app.post('/api/master/categories', authenticateToken, async (req, res) => {
    const { domain_id, name } = req.body;
    try {
        await pool.execute('INSERT INTO master_categories (domain_id, category_name) VALUES (?, ?)', [domain_id, name]);
        res.json({ msg: "Category Added" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
app.put('/api/master/categories/:id', authenticateToken, async (req, res) => {
    const { name } = req.body;
    try {
        await pool.execute('UPDATE master_categories SET category_name = ? WHERE id = ?', [name, req.params.id]);
        res.json({ msg: "Category Updated" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
app.delete('/api/master/categories/:id', authenticateToken, async (req, res) => {
    try {
        await pool.execute('DELETE FROM master_categories WHERE id = ?', [req.params.id]);
        res.json({ msg: "Category deleted" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// ==========================================
// 4. SYSTEM EMAIL ROUTES (Managed Mail IDs)
// ==========================================
app.get('/api/master/system-emails', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM system_emails ORDER BY label ASC');
        res.json(rows);
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.post('/api/master/system-emails', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) return res.status(403).json({ msg: "Super admin only" });
    const { label, email } = req.body;
    if (!label || !email) return res.status(400).json({ msg: "Label and Email are required" });
    try {
        const nowStr = getISTString();
        await pool.execute(
            'INSERT INTO system_emails (label, email, is_active, created_at) VALUES (?, ?, 1, ?)',
            [label.trim(), email.trim(), nowStr]
        );
        res.json({ msg: "System email added" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.put('/api/master/system-emails/:id', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) return res.status(403).json({ msg: "Super admin only" });
    const { label, email, is_active } = req.body;
    try {
        await pool.execute(
            'UPDATE system_emails SET label = ?, email = ?, is_active = ? WHERE id = ?',
            [label.trim(), email.trim(), is_active ?? 1, req.params.id]
        );
        res.json({ msg: "System email updated" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.delete('/api/master/system-emails/:id', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) return res.status(403).json({ msg: "Super admin only" });
    try {
        await pool.execute('DELETE FROM system_emails WHERE id = ?', [req.params.id]);
        res.json({ msg: "System email deleted" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// GET unique agent companies
app.get('/api/master/agent-companies', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute("SELECT DISTINCT company_name FROM users WHERE user_type = 'AGENT' AND company_name IS NOT NULL AND company_name != '' ORDER BY company_name ASC");
        res.json(rows.map(r => r.company_name));
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// ==========================================
// 5. VALUE ROUTES (Step 3 - Sub-values)
// ==========================================
app.post('/api/master/values', authenticateToken, async (req, res) => {
    const { category_id, value } = req.body;
    try {
        await pool.execute('INSERT INTO master_values (category_id, sub_value) VALUES (?, ?)', [category_id, value]);
        res.json({ msg: "Value Added" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
app.post('/api/master/values/bulk', authenticateToken, async (req, res) => {
    const { category_id, values } = req.body;
    if (!category_id || !Array.isArray(values) || values.length === 0) {
        return res.status(400).json({ msg: "category_id and an array of values are required" });
    }

    // Clean and deduplicate values
    const uniqueValues = [...new Set(values.map(v => v ? v.toString().trim() : '').filter(v => v !== ''))];
    if (uniqueValues.length === 0) {
        return res.status(400).json({ msg: "No valid values provided" });
    }

    let connection;
    try {
        connection = await pool.getConnection();
        await connection.beginTransaction();

        // We do a simple loop here. Performance for a few hundred items is fine.
        for (const val of uniqueValues) {
            // Optional: You could check if it exists first to prevent duplicates per category
            await connection.execute(
                'INSERT INTO master_values (category_id, sub_value) VALUES (?, ?)',
                [category_id, val]
            );
        }

        await connection.commit();
        res.json({ msg: `${uniqueValues.length} Values Added` });
    } catch (err) {
        if (connection) await connection.rollback();
        console.error("Bulk insert Error:", err);
        res.status(500).json({ error: "Failed to upload bulk values", details: err.message });
    } finally {
        if (connection) connection.release();
    }
});
app.put('/api/master/values/:id', authenticateToken, async (req, res) => {
    const { sub_value } = req.body;
    try {
        await pool.execute('UPDATE master_values SET sub_value = ? WHERE id = ?', [sub_value, req.params.id]);
        res.json({ msg: "Value Updated" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
app.delete('/api/master/values/:id', authenticateToken, async (req, res) => {
    try {
        await pool.execute('DELETE FROM master_values WHERE id = ?', [req.params.id]);
        res.json({ msg: "Value deleted" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// ==========================================
// 4. USER HIERARCHY ROUTES
// ==========================================
app.get('/api/master/user-hierarchy', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute(
            'SELECT id, tier, role_name, is_active FROM master_user_hierarchy ORDER BY FIELD(tier, "SUPER_ADMIN","ADMIN","STAFF"), role_name'
        );
        res.json(rows);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
app.post('/api/master/user-hierarchy', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized" });
    }
    const { tier, role_name, is_active } = req.body;
    if (!['SUPER_ADMIN', 'ADMIN', 'STAFF'].includes(tier) || !role_name) {
        return res.status(400).json({ msg: "Invalid payload" });
    }
    try {
        await pool.execute(
            'INSERT INTO master_user_hierarchy (tier, role_name, is_active) VALUES (?, ?, ?)',
            [tier, role_name, is_active === 0 ? 0 : 1]
        );
        res.json({ msg: "Hierarchy role added" });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
app.put('/api/master/user-hierarchy/:id', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized" });
    }
    const { tier, role_name, is_active } = req.body;
    if (!['SUPER_ADMIN', 'ADMIN', 'STAFF'].includes(tier) || !role_name) {
        return res.status(400).json({ msg: "Invalid payload" });
    }
    try {
        await pool.execute(
            'UPDATE master_user_hierarchy SET tier = ?, role_name = ?, is_active = ? WHERE id = ?',
            [tier, role_name, Number(is_active) ? 1 : 0, req.params.id]
        );
        res.json({ msg: "Hierarchy role updated" });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
app.delete('/api/master/user-hierarchy/:id', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized" });
    }
    try {
        await pool.execute('DELETE FROM master_user_hierarchy WHERE id = ?', [req.params.id]);
        res.json({ msg: "Hierarchy role deleted" });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});
// ==========================================
// 5. THE MASTER VIEW (Full Structure)
// ==========================================
app.get('/api/master/full-structure', async (req, res) => {
    try {
        const [rows] = await pool.execute(`
            SELECT 
                d.id as domainId, d.name as domainName, d.sequence as domainSequence, d.icon_type as domainIconType, d.icon_name as domainIconName, d.logo_url as domainLogoUrl, d.submenus as domainSubmenus,
                c.id as catId, c.category_name as catName,
                v.id as valId, v.sub_value as valName
            FROM master_domains d
            LEFT JOIN master_categories c ON d.id = c.domain_id
            LEFT JOIN master_values v ON c.id = v.category_id
            ORDER BY COALESCE(d.sequence, 999999), d.name, c.category_name
        `);
        const structure = rows.reduce((acc, row) => {
            let domain = acc.find(d => d.id === row.domainId);
            if (!domain) {
                domain = {
                    id: row.domainId,
                    name: row.domainName,
                    sequence: row.domainSequence ?? null,
                    icon_type: row.domainIconType || 'default',
                    icon_name: row.domainIconName || null,
                    logo_url: row.domainLogoUrl || null,
                    submenus: (() => {
                        try {
                            const parsed = JSON.parse(row.domainSubmenus || '[]');
                            return Array.isArray(parsed) ? parsed : [];
                        } catch {
                            return [];
                        }
                    })(),
                    categories: []
                };
                acc.push(domain);
            }
            if (row.catId) {
                let cat = domain.categories.find(c => c.id === row.catId);
                if (!cat) {
                    cat = { id: row.catId, category_name: row.catName, values: [] };
                    domain.categories.push(cat);
                }
                if (row.valId) {
                    cat.values.push({ id: row.valId, sub_value: row.valName });
                }
            }
            return acc;
        }, []);
        res.json(structure);
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// DELETE LEAD
// Note: Changed 'verifyToken' to 'authenticateToken' to match your error logs
app.delete('/api/leads/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { remarks } = req.body; // Can accept remarks from body
    try {
        const deletedBy = req.user?.id && req.user?.name
            ? `(${req.user.id}) ${req.user.name}`
            : (req.user?.name || 'System');
        // Soft delete: keep row in DB and just mark it inactive
        const [result] = await pool.query(
            'UPDATE leads SET is_active = 0, deleted_at = NOW(), deleted_by = ?, deletion_remark = ? WHERE id = ?',
            [deletedBy, remarks || null, id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: "Lead not found" });
        }
        res.status(200).json({ message: "Lead soft-deleted successfully" });
    } catch (err) {
        console.error("DATABASE ERROR:", err);
        res.status(500).json({ error: "Internal Server Error", details: err.message });
    }
});

// RESTORE LEAD (Super Admin Only)
app.patch('/api/leads/:id/restore', authenticateToken, async (req, res) => {
    const { id } = req.params;
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized to restore leads." });
    }
    try {
        const [result] = await pool.query(
            'UPDATE leads SET is_active = 1, deleted_at = NULL, deleted_by = NULL WHERE id = ?',
            [id]
        );
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: "Lead not found" });
        }
        res.status(200).json({ message: "Lead restored successfully" });
    } catch (err) {
        console.error("RESTORE ERROR:", err);
        res.status(500).json({ error: "Internal Server Error" });
    }
});

// BULK RESTORE LEADS (Super Admin Only)
app.patch('/api/admin/leads/bulk-restore', authenticateToken, async (req, res) => {
    const { ids } = req.body;
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized for bulk restoration." });
    }
    if (!Array.isArray(ids) || ids.length === 0) {
        return res.status(400).json({ msg: "No lead IDs provided." });
    }
    try {
        const [result] = await pool.query(
            'UPDATE leads SET is_active = 1, deleted_at = NULL, deleted_by = NULL WHERE id IN (?) AND is_active = 0',
            [ids]
        );
        res.status(200).json({ message: `${result.affectedRows} records restored successfully.` });
    } catch (err) {
        console.error("BULK RESTORE ERROR:", err);
        res.status(500).json({ error: "Internal Server Error" });
    }
});

// PERMANENTLY DELETE LEADS (Super Admin Only)
app.delete('/api/admin/leads/permanent-delete', authenticateToken, async (req, res) => {
    const { ids } = req.body;
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized for permanent deletion." });
    }
    if (!Array.isArray(ids) || ids.length === 0) {
        return res.status(400).json({ msg: "No lead IDs provided." });
    }
    try {
        // Clean up associated data across all related tables
        await pool.query('DELETE FROM lead_documents WHERE lead_id IN (?)', [ids]);
        await pool.query('DELETE FROM lead_remarks_messages WHERE lead_id IN (?)', [ids]);
        await pool.query('DELETE FROM lead_remarks_messages_history WHERE lead_id IN (?)', [ids]);
        await pool.query('DELETE FROM lead_edit_history WHERE lead_id IN (?)', [ids]);

        const [result] = await pool.query('DELETE FROM leads WHERE id IN (?) AND is_active = 0', [ids]);
        res.status(200).json({ message: `${result.affectedRows} records permanently deleted.` });
    } catch (err) {
        console.error("PERMANENT DELETE ERROR:", err);
        res.status(500).json({ error: "Internal Server Error" });
    }
});

// FETCH DELETED LEADS (Super Admin Only)
app.get('/api/admin/deleted-leads', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Unauthorized access." });
    }
    try {
        const [rows] = await pool.execute(
            'SELECT * FROM leads WHERE is_active = 0 ORDER BY deleted_at DESC'
        );
        res.json(rows);
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// ==========================================
// DOCUMENT MANAGEMENT SYSTEM
// ==========================================

// 1. MASTER DOCUMENTATION (New System)
app.get('/api/master/documents', authenticateToken, async (req, res) => {
    try {
        const [docs] = await pool.execute('SELECT * FROM master_documentation ORDER BY document_name ASC');
        const [mappings] = await pool.execute('SELECT * FROM master_documentation_mappings');

        const result = docs.map(d => ({
            ...d,
            access_configs: mappings.filter(m => m.doc_id === d.id)
        }));

        res.json(result);
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.post('/api/master/documents', authenticateToken, async (req, res) => {
    if (!isAdminUser(req.user)) return res.status(403).json({ msg: "Admin only" });
    const { documentName, isMandatory, access_configs } = req.body;

    let connection;
    try {
        connection = await pool.getConnection();
        await connection.beginTransaction();

        const [result] = await connection.query(
            'INSERT INTO master_documentation (document_name, is_mandatory) VALUES (?, ?)',
            [documentName, isMandatory ? 1 : 0]
        );
        const docId = result.insertId;

        if (access_configs && access_configs.length > 0) {
            for (const cfg of access_configs) {
                await connection.query(
                    'INSERT INTO master_documentation_mappings (doc_id, domain_id, category_id, value_id) VALUES (?, ?, ?, ?)',
                    [docId, cfg.domain_id || null, cfg.category_id || null, cfg.value_id || null]
                );
            }
        }

        await connection.commit();
        res.json({ msg: "Document added successfully", id: docId });
    } catch (err) {
        if (connection) await connection.rollback();
        res.status(500).json({ error: err.message });
    } finally {
        if (connection) connection.release();
    }
});

app.put('/api/master/documents/:id', authenticateToken, async (req, res) => {
    if (!isAdminUser(req.user)) return res.status(403).json({ msg: "Admin only" });
    const { id } = req.params;
    const { documentName, isMandatory, access_configs } = req.body;

    let connection;
    try {
        connection = await pool.getConnection();
        await connection.beginTransaction();

        await connection.query(
            'UPDATE master_documentation SET document_name = ?, is_mandatory = ? WHERE id = ?',
            [documentName, isMandatory ? 1 : 0, id]
        );

        // Delete old mappings and insert new ones
        await connection.query('DELETE FROM master_documentation_mappings WHERE doc_id = ?', [id]);

        if (access_configs && access_configs.length > 0) {
            for (const cfg of access_configs) {
                await connection.query(
                    'INSERT INTO master_documentation_mappings (doc_id, domain_id, category_id, value_id) VALUES (?, ?, ?, ?)',
                    [id, cfg.domain_id || null, cfg.category_id || null, cfg.value_id || null]
                );
            }
        }

        await connection.commit();
        res.json({ msg: "Document updated successfully" });
    } catch (err) {
        if (connection) await connection.rollback();
        res.status(500).json({ error: err.message });
    } finally {
        if (connection) connection.release();
    }
});

app.delete('/api/master/documents/:id', authenticateToken, async (req, res) => {
    if (!isAdminUser(req.user)) return res.status(403).json({ msg: "Admin only" });
    try {
        await pool.execute('DELETE FROM master_documentation WHERE id = ?', [req.params.id]);
        res.json({ msg: "Document deleted successfully" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// Legacy backward compatibility for old UI if needed (Optional, but let's remove if we are fully refactoring)
app.get('/api/master/documents/:level/:parentId', authenticateToken, async (req, res) => {
    const { level, parentId } = req.params;
    try {
        // Return documents from the new system that match this specific level
        let sql = `
            SELECT d.* 
            FROM master_documentation d
            JOIN master_documentation_mappings m ON d.id = m.doc_id
            WHERE `;

        let params = [];
        if (level === 'domain') {
            sql += 'm.domain_id = ? AND m.category_id IS NULL AND m.value_id IS NULL';
            params.push(parentId);
        } else if (level === 'category') {
            sql += 'm.category_id = ? AND m.value_id IS NULL';
            params.push(parentId);
        } else if (level === 'value') {
            sql += 'm.value_id = ?';
            params.push(parentId);
        }

        const [rows] = await pool.execute(sql, params);
        res.json(rows);
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// 2. LEAD DOCUMENTS management
app.get('/api/leads/:id/documents', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute(
            'SELECT * FROM lead_documents WHERE lead_id = ? ORDER BY uploaded_at DESC',
            [req.params.id]
        );
        res.json(rows);
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.get('/api/leads/:id/document-requirements', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        // 1. Get lead details
        const [leadRows] = await pool.execute(
            'SELECT domain, category, interested_in FROM leads WHERE id = ?',
            [id]
        );
        if (leadRows.length === 0) return res.status(404).json({ msg: "Lead not found" });
        const lead = leadRows[0];

        // 2. Resolve IDs for each level using flexible matching
        const normalizedDomain = normalizeContactDomain(lead.domain);

        // Domain ID
        const [domainRows] = await pool.execute(
            'SELECT id FROM master_domains WHERE LOWER(name) LIKE LOWER(?) OR LOWER(?) LIKE CONCAT("%", LOWER(name), "%")',
            [`%${normalizedDomain}%`, normalizedDomain]
        );
        const domainId = domainRows[0]?.id;

        // Category ID
        let categoryId = null;
        if (lead.category && lead.category !== 'Mobile Enquiry') {
            const [catRows] = await pool.execute(
                'SELECT id FROM master_categories WHERE LOWER(category_name) LIKE LOWER(?) OR LOWER(?) LIKE CONCAT("%", LOWER(category_name), "%")',
                [`%${lead.category}%`, lead.category]
            );
            categoryId = catRows[0]?.id;
        }

        // Value ID (Interested In)
        let valueId = null;
        if (lead.interested_in) {
            const [valRows] = await pool.execute(
                'SELECT id FROM master_values WHERE LOWER(sub_value) LIKE LOWER(?) OR LOWER(?) LIKE CONCAT("%", LOWER(sub_value), "%")',
                [`%${lead.interested_in}%`, lead.interested_in]
            );
            valueId = valRows[0]?.id;
        }

        console.log(`[DocMatch] Lead ${id}: Domain(${lead.domain} -> ${normalizedDomain} -> ID:${domainId}), Cat(${lead.category} -> ID:${categoryId}), Val(${lead.interested_in} -> ID:${valueId})`);

        // 3. Fetch all requirements using the new many-to-many structure
        let requirements = [];
        if (domainId) {
            const [reqRows] = await pool.execute(
                `SELECT DISTINCT d.* 
                 FROM master_documentation d
                 JOIN master_documentation_mappings m ON d.id = m.doc_id
                 WHERE m.domain_id = ?
                   AND (m.category_id IS NULL OR m.category_id = ?)
                   AND (m.value_id IS NULL OR m.value_id = ?)`,
                [domainId, categoryId || null, valueId || null]
            );
            requirements = reqRows;
        }

        // 4. Fetch already uploaded documents for this lead
        const [uploadRows] = await pool.execute(
            'SELECT * FROM lead_documents WHERE lead_id = ?',
            [id]
        );

        res.json({
            requirements,
            uploads: uploadRows
        });
    } catch (err) {
        console.error("DocReq Error:", err);
        res.status(500).json({ error: err.message });
    }
});

// ── CANDIDATE PHOTO ─────────────────────────────────────────────────────────
// GET: fetch current photo URL for a lead
app.get('/api/leads/:id/photo', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute('SELECT candidate_photo FROM leads WHERE id = ?', [id]);
        if (!rows.length) return res.status(404).json({ error: 'Lead not found' });
        res.json({ photo_url: rows[0].candidate_photo || null });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// POST: upload / replace candidate photo (images only, 5 MB max)
const uploadPhoto = multer({
    storage: multer.diskStorage({
        destination: (req, file, cb) => {
            const photoDir = path.join(__dirname, 'uploads/photos');
            if (!fs.existsSync(photoDir)) fs.mkdirSync(photoDir, { recursive: true });
            cb(null, 'uploads/photos');
        },
        filename: (req, file, cb) => {
            const ext = path.extname(file.originalname).toLowerCase();
            cb(null, `lead_${req.params.id}_${Date.now()}${ext}`);
        }
    }),
    limits: { fileSize: 20 * 1024 * 1024 }, // 20 MB max
    fileFilter: (req, file, cb) => {
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (allowed.includes(file.mimetype)) cb(null, true);
        else cb(new Error('Only JPEG, PNG, or WEBP images are allowed'));
    }
});

app.post('/api/leads/:id/photo', authenticateToken, (req, res, next) => {
    uploadPhoto.single('photo')(req, res, (err) => {
        if (err instanceof multer.MulterError) {
            return res.status(400).json({ error: `Upload error: ${err.message}` });
        } else if (err) {
            return res.status(400).json({ error: err.message });
        }
        next();
    });
}, async (req, res) => {
    const { id } = req.params;
    if (!req.file) return res.status(400).json({ error: 'No image uploaded' });
    try {
        const photoUrl = `uploads/photos/${req.file.filename}`;
        const [beforeRows] = await pool.execute('SELECT candidate_photo FROM leads WHERE id = ?', [id]);
        if (beforeRows.length === 0) {
            return res.status(404).json({ error: 'Lead not found' });
        }
        await pool.execute('UPDATE leads SET candidate_photo = ? WHERE id = ?', [photoUrl, id]);
        await recordLeadEditHistory(id, 'candidate_photo', beforeRows[0].candidate_photo, photoUrl, formatWho(req.user));
        res.json({ msg: 'Photo uploaded successfully', photo_url: photoUrl });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// DELETE: remove candidate photo
app.delete('/api/leads/:id/photo', authenticateToken, async (req, res) => {
    const { id } = req.params;
    try {
        const [rows] = await pool.execute('SELECT candidate_photo FROM leads WHERE id = ?', [id]);
        if (rows.length === 0) {
            return res.status(404).json({ error: 'Lead not found' });
        }
        if (rows[0]?.candidate_photo) {
            const fullPath = path.join(__dirname, rows[0].candidate_photo);
            if (fs.existsSync(fullPath)) fs.unlink(fullPath, () => {});
        }
        await pool.execute('UPDATE leads SET candidate_photo = NULL WHERE id = ?', [id]);
        await recordLeadEditHistory(id, 'candidate_photo', rows[0].candidate_photo, null, formatWho(req.user));
        res.json({ msg: 'Photo removed' });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

app.post('/api/leads/:id/documents/upload', authenticateToken, uploadDocuments.single('file'), async (req, res) => {
    const { id } = req.params;
    const { documentName, requirementId } = req.body;
    const uploadedBy = formatWho(req.user);

    if (!req.file) return res.status(400).json({ msg: "No file uploaded" });

    try {
        const filePath = `uploads/documents/${req.file.filename}`;

        // Get lead details
        const [leadRows] = await pool.execute('SELECT domain, student_name, lead_code FROM leads WHERE id = ?', [id]);
        if (leadRows.length === 0) return res.status(404).json({ msg: "Lead not found" });
        const lead = leadRows[0];
        const isOverseas = lead.domain && lead.domain.toLowerCase() === 'overseas';

        // We use an UPSERT style logic: if requirementId exists for this lead, update it.
        // If no requirementId (ad-hoc), we always insert.
        if (requirementId) {
            const [existing] = await pool.execute(
                'SELECT id, file_path FROM lead_documents WHERE lead_id = ? AND requirement_id = ?',
                [id, requirementId]
            );

            if (existing.length > 0) {
                if (isOverseas) {
                    return res.status(400).json({ msg: "Re-upload restricted for overseas candidates." });
                }

                await pool.execute(
                    `UPDATE lead_documents 
                     SET file_name = ?, file_path = ?, file_type = ?, status = 'Uploaded', 
                         uploaded_by = ?, uploaded_at = NOW() 
                     WHERE id = ?`,
                    [req.file.originalname, filePath, req.file.mimetype, uploadedBy, existing[0].id]
                );
                await recordLeadEditHistory(id, 'documents', '', documentName || req.file.originalname, uploadedBy);

                // Send email notification after update
                try {
                    const transporter = createMailTransporter();
                    if (transporter) {
                        await transporter.sendMail({
                            from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                            to: 'bluestoneocs@gmail.com, info@bluestoneocs.com, bluestonesoftwaredeveloper@gmail.com',
                            subject: `Document Uploaded: ${documentName || req.file.originalname} for ${lead.student_name}`,
                            text: `A document has been uploaded.\n\nCandidate: ${lead.student_name} (${lead.lead_code || id})\nDocument Name: ${documentName || req.file.originalname}\nFile Name: ${req.file.originalname}\nUploaded By: ${uploadedBy}\nDate: ${new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })}`
                        });
                    }
                } catch (e) {
                    console.error("Failed to send document upload email:", e);
                }

                return res.json({ msg: "File updated successfully", filePath });
            }
        }

        await pool.execute(
            `INSERT INTO lead_documents 
            (lead_id, requirement_id, document_name, file_name, file_path, file_type, status, uploaded_by, uploaded_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'Uploaded', ?, NOW())`,
            [id, requirementId || null, documentName, req.file.originalname, filePath, req.file.mimetype, uploadedBy]
        );
        await recordLeadEditHistory(id, 'documents', '', documentName || req.file.originalname, uploadedBy);

        // Send email notification after insert
        try {
            const transporter = createMailTransporter();
            if (transporter) {
                await transporter.sendMail({
                    from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                    to: 'bluestoneocs@gmail.com, info@bluestoneocs.com, bluestonesoftwaredeveloper@gmail.com',
                    subject: `Document Uploaded: ${documentName || req.file.originalname} for ${lead.student_name}`,
                    text: `A document has been uploaded.\n\nCandidate: ${lead.student_name} (${lead.lead_code || id})\nDocument Name: ${documentName || req.file.originalname}\nFile Name: ${req.file.originalname}\nUploaded By: ${uploadedBy}\nDate: ${new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })}`
                });
            }
        } catch (e) {
            console.error("Failed to send document upload email:", e);
        }

        res.json({ msg: "File uploaded successfully", filePath });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

// Upload document with compose-style email notification
app.post('/api/leads/:id/documents/upload-with-mail', authenticateToken, uploadDocuments.single('file'), async (req, res) => {
    const { id } = req.params;
    const { documentName, requirementId, subject, message, ccEmails } = req.body;
    const uploadedBy = formatWho(req.user);

    if (!req.file) return res.status(400).json({ msg: "No file uploaded" });

    try {
        const filePath = `uploads/documents/${req.file.filename}`;

        // Get lead details
        const [leadRows] = await pool.execute(
            'SELECT domain, student_name, lead_code, email FROM leads WHERE id = ?',
            [id]
        );
        if (leadRows.length === 0) return res.status(404).json({ msg: "Lead not found" });
        const lead = leadRows[0];
        const isOverseas = lead.domain && lead.domain.toLowerCase() === 'overseas';

        // Save document to DB
        if (requirementId) {
            const [existing] = await pool.execute(
                'SELECT id, file_path FROM lead_documents WHERE lead_id = ? AND requirement_id = ?',
                [id, requirementId]
            );
            if (existing.length > 0) {
                if (isOverseas && existing[0].file_path) {
                    return res.status(400).json({ msg: "Re-upload restricted for overseas candidates." });
                }
                await pool.execute(
                    `UPDATE lead_documents SET file_name=?, file_path=?, file_type=?, status='Uploaded', uploaded_by=?, uploaded_at=NOW() WHERE id=?`,
                    [req.file.originalname, filePath, req.file.mimetype, uploadedBy, existing[0].id]
                );
            } else {
                await pool.execute(
                    `INSERT INTO lead_documents (lead_id, requirement_id, document_name, file_name, file_path, file_type, status, uploaded_by, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, 'Uploaded', ?, NOW())`,
                    [id, requirementId, documentName, req.file.originalname, filePath, req.file.mimetype, uploadedBy]
                );
            }
        } else {
            await pool.execute(
                `INSERT INTO lead_documents (lead_id, requirement_id, document_name, file_name, file_path, file_type, status, uploaded_by, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, 'Uploaded', ?, NOW())`,
                [id, null, documentName, req.file.originalname, filePath, req.file.mimetype, uploadedBy]
            );
        }

        await recordLeadEditHistory(id, 'documents', '', documentName || req.file.originalname, uploadedBy);

        // Send email notification
        try {
            const transporter = createMailTransporter();
            if (transporter) {
                const mailSubject = subject || `Document Uploaded: ${documentName || req.file.originalname} for ${lead.student_name}`;
                const mailBody = message
                    ? `${message}\n\n---\nCandidate: ${lead.student_name} (${lead.lead_code || id})\nDocument: ${documentName || req.file.originalname}\nUploaded By: ${uploadedBy}`
                    : `A new document has been uploaded.\n\nCandidate: ${lead.student_name} (${lead.lead_code || id})\nDocument: ${documentName || req.file.originalname}\nUploaded By: ${uploadedBy}\nDate: ${new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })}`;

                const mailOptions = {
                    from: SMTP_CONFIG.from || SMTP_CONFIG.user,
                    to: 'bluestoneocs@gmail.com, info@bluestoneocs.com, bluestonesoftwaredeveloper@gmail.com',
                    bcc: req.body.sendToCandidate === 'true' && lead.email ? lead.email : undefined,
                    subject: mailSubject,
                    text: mailBody,
                    attachments: [{
                        filename: req.file.originalname,
                        path: path.join(__dirname, filePath)
                    }]
                };

                await transporter.sendMail(mailOptions);
            }
        } catch (mailErr) {
            console.error("Failed to send document upload mail:", mailErr);
            // Don't fail the upload if mail fails
        }

        res.json({ msg: "File uploaded and notification sent", filePath });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.post('/api/leads/:id/documents/notify-download', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { filename } = req.body;
    const downloadedBy = formatWho(req.user);

    try {
        const [leadRows] = await pool.execute('SELECT domain, student_name, lead_code FROM leads WHERE id = ?', [id]);
        if (leadRows.length === 0) return res.status(404).json({ msg: "Lead not found" });
        const lead = leadRows[0];
        const isOverseas = lead.domain && lead.domain.toLowerCase() === 'overseas';

        if (isOverseas) {
            await transporter.sendMail({
                from: "bluestonesoftwaredeveloper@gmail.com",
                to: "bluestoneocs@gmail.com",
                subject: `Document Downloaded: ${filename} for ${lead.student_name}`,
                text: `A document has been downloaded for an Overseas candidate:\n\nCandidate: ${lead.student_name} (${lead.lead_code || id})\nDocument Name: ${filename}\nDownloaded By: ${downloadedBy}\nDate: ${new Date().toLocaleString()}`
            });
        }
        res.json({ msg: "Notification sent" });
    } catch (err) {
        console.error("Failed to send download notification email:", err);
        res.status(200).json({ msg: "Notification attempted", error: err.message });
    }
});

app.post('/api/leads/:id/documents/checklist', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const { requirementId, documentName } = req.body;
    const uploadedBy = formatWho(req.user);

    if (!requirementId) return res.status(400).json({ msg: "Requirement ID is required for checklist" });

    try {
        const [existing] = await pool.execute(
            'SELECT id, status, file_path, document_name FROM lead_documents WHERE lead_id = ? AND requirement_id = ?',
            [id, requirementId]
        );

        if (existing.length > 0) {
            // Already there. If it's just 'Collected', we toggle it OFF (delete).
            // If it has a file, we don't allow unchecking via checklist logic (must delete file).
            if (existing[0].status === 'Collected') {
                await pool.execute('DELETE FROM lead_documents WHERE id = ?', [existing[0].id]);
                await recordLeadEditHistory(id, 'documents', existing[0].document_name || documentName, '', uploadedBy);
                return res.json({ msg: "Document unchecked", action: 'removed' });
            } else {
                return res.json({ msg: "Document already uploaded", action: 'none' });
            }
        }

        // Not there, create a 'Collected' record
        await pool.execute(
            `INSERT INTO lead_documents 
            (lead_id, requirement_id, document_name, status, uploaded_by, uploaded_at) 
            VALUES (?, ?, ?, 'Collected', ?, NOW())`,
            [id, requirementId, documentName, uploadedBy]
        );
        await recordLeadEditHistory(id, 'documents', '', documentName, uploadedBy);
        res.json({ msg: "Document marked as Collected", action: 'added' });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.patch('/api/leads/:id/documents/:docId/status', authenticateToken, async (req, res) => {
    const { docId } = req.params;
    const { status } = req.body;
    const identifier = formatWho(req.user);
    if (!['Pending', 'Uploaded', 'Verified', 'Rejected'].includes(status)) {
        return res.status(400).json({ msg: "Invalid status" });
    }
    try {
        const [beforeRows] = await pool.execute(
            'SELECT status, document_name FROM lead_documents WHERE id = ? AND lead_id = ?',
            [docId, req.params.id]
        );
        await pool.execute(
            'UPDATE lead_documents SET status = ?, verified_by = ?, verified_at = CASE WHEN ? = "Verified" THEN NOW() ELSE verified_at END WHERE id = ?',
            [status, identifier, status, docId]
        );
        if (beforeRows.length > 0) {
            await recordLeadEditHistory(
                req.params.id,
                'documents',
                `${beforeRows[0].document_name || 'Document'}: ${beforeRows[0].status}`,
                `${beforeRows[0].document_name || 'Document'}: ${status}`,
                identifier
            );
        }
        res.json({ msg: `Document status updated to ${status}` });
    } catch (err) { res.status(500).json({ error: err.message }); }
});

app.delete('/api/leads/:id/documents/:docId', authenticateToken, async (req, res) => {
    const { docId } = req.params;
    try {
        const [rows] = await pool.execute(
            'SELECT file_path, document_name FROM lead_documents WHERE id = ? AND lead_id = ?',
            [docId, req.params.id]
        );
        if (rows.length > 0 && rows[0].file_path) {
            const fullPath = path.join(__dirname, rows[0].file_path);
            if (fs.existsSync(fullPath)) fs.unlinkSync(fullPath);
        }
        await pool.execute('DELETE FROM lead_documents WHERE id = ?', [docId]);
        if (rows.length > 0) {
            await recordLeadEditHistory(req.params.id, 'documents', rows[0].document_name || 'Document', '', formatWho(req.user));
        }
        res.json({ msg: "Document deleted" });
    } catch (err) { res.status(500).json({ error: err.message }); }
});
// EDIT LEAD (UPDATE)
app.put('/api/leads/:id', authenticateToken, async (req, res) => {
    const { id } = req.params;
    const {
        student_name, last_name, email, phone, category, interested_in, intake,
        passport_no, university_student_id, university_name,
        course_name, primary_status, secondary_status, remarks,
        college_contacts
    } = req.body;
    try {
        const [beforeRows] = await pool.query(
            'SELECT student_name, last_name, email, phone, category, interested_in, intake, passport_no, university_student_id, university_name, course_name, primary_status, secondary_status, remarks, college_contacts FROM leads WHERE id = ?',
            [id]
        );
        if (beforeRows.length === 0) {
            return res.status(404).json({ message: "Lead not found" });
        }
        const before = beforeRows[0];

        const sql = `UPDATE leads SET 
            student_name=?, last_name=?, email=?, phone=?, category=?, interested_in=?, intake=?, 
            passport_no=?, university_student_id=?, university_name=?, 
            course_name=?, primary_status=?, secondary_status=?, remarks=?, college_contacts=? 
            WHERE id=?`;

        const params = [
            student_name !== undefined ? student_name : before.student_name,
            last_name !== undefined ? last_name : before.last_name,
            email !== undefined ? email : before.email,
            phone !== undefined ? phone : before.phone,
            category !== undefined ? category : before.category,
            interested_in !== undefined ? interested_in : before.interested_in,
            intake !== undefined ? intake : before.intake,
            passport_no !== undefined ? (passport_no || null) : before.passport_no,
            university_student_id !== undefined ? (university_student_id || null) : before.university_student_id,
            university_name !== undefined ? (university_name || null) : before.university_name,
            course_name !== undefined ? (course_name || null) : before.course_name,
            primary_status !== undefined ? (primary_status || null) : before.primary_status,
            secondary_status !== undefined ? (secondary_status || null) : before.secondary_status,
            remarks !== undefined ? (remarks || null) : before.remarks,
            college_contacts !== undefined ? (college_contacts ? JSON.stringify(college_contacts) : null) : before.college_contacts,
            id
        ];

        const [result] = await pool.query(sql, params);
        if (result.affectedRows === 0) {
            return res.status(404).json({ message: "Lead not found" });
        }

        const changedBy = req.user?.name || 'System';
        const trackFields = [
            'student_name', 'last_name', 'email', 'phone', 'category', 'interested_in',
            'intake', 'passport_no', 'university_student_id', 'university_name',
            'course_name', 'primary_status', 'secondary_status', 'remarks', 'college_contacts'
        ];

        for (const field of trackFields) {
            const nextValue = req.body[field] !== undefined ? req.body[field] : before[field];
            await recordLeadEditHistory(id, field, before[field], nextValue, changedBy);
        }
        res.status(200).json({ message: "Lead updated successfully" });
    } catch (err) {
        console.error("DATABASE ERROR:", err);
        res.status(500).json({ error: "Internal Server Error", details: err.message });
    }
});

app.get('/api/dashboard/enquiry-trends', authenticateToken, async (req, res) => {
    const { range = 'week', domain: selectedDomain = 'All', startDate, endDate, date: specificDate, tzOffset, source_type, company_name } = req.query;
    const { userId: filterUserId } = req.query;
    const isSuperAdmin = isSuperAdminUser(req.user);
    const { domain: userDomain, id: loggedUserId } = req.user;

    try {
        const combinedBase = `
            (
                SELECT domain, status, created_at, assigned_to, source_type, company_name
                FROM leads
                WHERE is_active = 1
                UNION ALL
                SELECT service_type AS domain, 'New' AS status, created_at, NULL AS assigned_to, 'Mobile' AS source_type, NULL AS company_name
                FROM bgoi_enquiries e
                WHERE NOT EXISTS (
                    SELECT 1 FROM leads l 
                    WHERE l.source = 'Mobile App (Live)' AND l.student_name = e.candidate_name AND l.created_at = e.created_at
                )
            ) AS combined
        `;

        let where = " WHERE 1=1";
        const params = [];

        // 0. Source/Company Filter
        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                where += " AND (combined.source_type = 'Agent' OR (combined.company_name IS NOT NULL AND combined.company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')))";
            } else if (source_type === 'Own') {
                where += " AND (combined.source_type = 'Own' OR combined.source_type IS NULL OR combined.source_type = '') AND (combined.company_name IS NULL OR combined.company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            } else {
                where += ' AND combined.source_type = ?';
                params.push(source_type);
            }
        }

        if (company_name && company_name !== 'All') {
            where += ' AND company_name = ?';
            params.push(company_name);
        }

        // 0.5. Company Filter (Isolation for Agent/Franchise)
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            where += ' AND company_name = ?';
            params.push(req.user.company_name);
        }

        // 1. Staff/User Filter
        if (filterUserId && filterUserId !== 'All') {
            where += " AND assigned_to = ?";
            params.push(filterUserId);
        } else if (isStaffUser(req.user)) {
            where += " AND assigned_to = ?";
            params.push(loggedUserId);
        }

        // 2. Domain Filter (Security + Selection)
        if (!isSuperAdmin) {
            if (selectedDomain && selectedDomain !== 'All') {
                const authorized = String(userDomain || '').split(',').map(d => d.trim().toLowerCase());
                const effectiveDomain = authorized.includes(selectedDomain.toLowerCase().trim()) ? selectedDomain : userDomain;
                where = appendDomainCondition(where, params, effectiveDomain);
            } else {
                where = appendDomainCondition(where, params, userDomain);
            }
        } else if (selectedDomain && selectedDomain !== 'All') {
            where = appendDomainCondition(where, params, selectedDomain);
        }

        // 3. Timeframe Filter & Localization
        // Offset Calculation: DB is IST (+330 min). Local = IST + ((-tzOffset) - 330)
        const userTzOffset = parseInt(tzOffset || -330);
        const shiftMinutes = (-userTzOffset) - 330;

        let groupBy = "DATE(created_at)";
        let selectLabel = "DATE_FORMAT(created_at, '%Y-%m-%d')";

        if (range === 'day') {
            groupBy = `HOUR(DATE_ADD(created_at, INTERVAL ${shiftMinutes} MINUTE))`;
            selectLabel = `HOUR(DATE_ADD(created_at, INTERVAL ${shiftMinutes} MINUTE))`;
            if (specificDate) {
                where += " AND DATE(created_at) = ?";
                params.push(specificDate);
            } else {
                where += " AND DATE(created_at) = CURDATE()";
            }
        } else if (range === 'week') {
            where += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        } else if (range === 'month') {
            where += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        } else if (range === 'year') {
            where += " AND created_at >= DATE_SUB(CURDATE(), INTERVAL 365 DAY)";
        } else if (range === 'custom' && startDate && endDate) {
            where += " AND DATE(created_at) BETWEEN ? AND ?";
            params.push(startDate, endDate);
        }

        const sql = `
            SELECT 
                ${selectLabel} as label,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Follow Up' THEN 1 ELSE 0 END) as followup,
                SUM(CASE WHEN status = 'Enrolled' THEN 1 ELSE 0 END) as enrolled,
                SUM(CASE WHEN status = 'Dropped' THEN 1 ELSE 0 END) as dropped
            FROM ${combinedBase}
            ${where}
            GROUP BY ${groupBy}
            ORDER BY label ASC
        `;

        const [rawRows] = await pool.execute(sql, params);

        if (range === 'day') {
            const filled = [];
            for (let h = 0; h < 24; h++) {
                const found = rawRows.find(r => parseInt(r.label) === h);
                filled.push(found ? found : {
                    label: h,
                    enquiry: 0,
                    invalid: 0,
                    pending: 0,
                    followup: 0,
                    enrolled: 0,
                    dropped: 0,
                    total: 0
                });
            }
            return res.json(filled);
        }

        res.json(rawRows);
    } catch (err) {
        console.error("Enquiry Trends Error:", err);
        res.status(500).json({ error: err.message });
    }
});

app.get('/api/dashboard/trends-daily', async (req, res) => {
    const { domain } = req.query;
    const isAll = domain === 'All';
    const getDailyData = (status) => {
        return new Promise((resolve, reject) => {
            // Get data for last 30 days
            let sql = `
                SELECT DATE(created_at) as date, COUNT(*) as count 
                FROM leads 
                WHERE is_active = 1 AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                ${status ? 'AND status = ?' : ''}
                ${!isAll ? 'AND domain = ?' : ''}
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at) ASC
            `;
            const params = [];
            if (status) params.push(status);
            if (!isAll) params.push(domain);
            pool.query(sql, params, (err, results) => {
                if (err) return reject(err);
                // Fill gaps for dates with 0 activity
                const last30Days = [];
                for (let i = 29; i >= 0; i--) {
                    const d = new Date();
                    d.setDate(d.getDate() - i);
                    const dateStr = d.toISOString().split('T')[0];
                    const found = results.find(r => {
                        const dbDate = new Date(r.date).toISOString().split('T')[0];
                        return dbDate === dateStr;
                    });
                    last30Days.push({
                        date: dateStr,
                        value: found ? found.count : 0
                    });
                }
                resolve(last30Days);
            });
        });
    };
    try {
        const [enquiry, followup, admission] = await Promise.all([
            getDailyData('enquiry'),
            getDailyData('followup'),
            getDailyData('admission')
        ]);
        res.json({ enquiry, followup, admission });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

// NEW: GET Staff List for Dashboard Dropdown
// NEW: GET Staff List for Dashboard Dropdown
app.get('/api/dashboard/staff-list', authenticateToken, async (req, res) => {
    const { domain: selectedDomain, source_type, company_name } = req.query;
    const isSuperAdmin = isSuperAdminUser(req.user);
    const { domain: userDomain } = req.user;

    try {
        let sql = "SELECT id, name, domain, role, tier FROM users WHERE is_active = 1";
        const params = [];

        if (source_type && source_type !== 'All') {
            if (source_type === 'Agent') {
                sql += " AND company_name IS NOT NULL AND company_name NOT IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions')";
            } else if (source_type === 'Own') {
                sql += " AND (company_name IS NULL OR company_name IN ('-', 'Bluestone', 'BGOI', 'Bluestone Group of Institutions'))";
            }
        }

        if (company_name && company_name !== 'All') {
            sql += " AND company_name = ?";
            params.push(company_name);
        }

        if (!isSuperAdmin) {
            // Non-super-admins see users within their authorized domains
            const userType = req.user.user_type || 'CORE';
            if (userType === 'AGENT' || userType === 'FRANCHISE') {
                sql += " AND company_name = ?";
                params.push(req.user.company_name);
            } else {
                const authorized = getDomainAliases(userDomain);

                if (selectedDomain && selectedDomain !== 'All') {
                    const requested = getDomainAliases(selectedDomain);
                    const hasAccess = requested.every(r => authorized.includes(r));

                    if (hasAccess) {
                        sql = appendDomainCondition(sql, params, selectedDomain);
                    } else {
                        sql = appendDomainCondition(sql, params, userDomain);
                    }
                } else {
                    sql = appendDomainCondition(sql, params, userDomain);
                }
            }
        } else if (selectedDomain && selectedDomain !== 'All') {
            sql = appendDomainCondition(sql, params, selectedDomain);
        }

        sql += " ORDER BY name ASC";
        const [rows] = await pool.execute(sql, params);
        res.json(rows);
    } catch (err) {
        console.error("Staff List Error:", err);
        res.status(500).json({ error: err.message });
    }
});
// GET User Efficiency (Super Admin Only)
app.get('/api/admin/user-efficiency', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Access denied. Super Admin only." });
    }

    try {
        const { userId, date, range = 'week', tzOffset } = req.query;
        const userTzOffset = parseInt(tzOffset || -330);
        const shiftMin = (-userTzOffset) - 330;

        // 1. Individual Hourly Breakdown (Used for "View Hourly" drill-down)
        if (userId && date) {
            const sql = `
                SELECT 
                    HOUR(DATE_ADD(CONCAT(activity_date, ' ', activity_hour, ':00:00'), INTERVAL ? MINUTE)) as hour, 
                    SUM(active_minutes) as minutes
                FROM user_activity_log
                WHERE user_id = ? AND activity_date = ?
                GROUP BY HOUR(DATE_ADD(CONCAT(activity_date, ' ', activity_hour, ':00:00'), INTERVAL ? MINUTE))
                ORDER BY hour ASC
            `;
            const [rows] = await pool.execute(sql, [shiftMin, userId, date, shiftMin]);

            const hourlyData = [];
            for (let i = 0; i < 24; i++) {
                const found = rows.find(r => r.hour === i);
                hourlyData.push({
                    hour: i,
                    label: `${i > 12 ? i - 12 : (i === 0 ? 12 : i)}:00 ${i >= 12 ? 'PM' : 'AM'}`,
                    minutes: found ? found.minutes : 0,
                    hours: found ? parseFloat((found.minutes / 60).toFixed(2)) : 0
                });
            }
            return res.json({ hourlyData });
        }
        const { startDate, endDate } = req.query;
        let sql = '';
        let params = [];

        if (range === 'day') {
            // Aggregation by Hour for a specific day
            const targetDate = date || new Date().toISOString().split('T')[0];
            sql = `
                SELECT 
                    u.name as userName,
                    HOUR(DATE_ADD(CONCAT(al.activity_date, ' ', al.activity_hour, ':00:00'), INTERVAL ? MINUTE)) as label,
                    SUM(al.active_minutes) as value
                FROM users u
                JOIN user_activity_log al ON u.id = al.user_id
                WHERE al.activity_date = ? AND u.is_active = 1
                ${userId ? 'AND u.id = ?' : ''}
                GROUP BY u.id, label
                ORDER BY label ASC
            `;
            params = userId ? [shiftMin, targetDate, userId] : [shiftMin, targetDate];
        } else if (range === 'week') {
            // Aggregation by Day for a specific range or last 7 days
            const whereClause = startDate && endDate
                ? `al.activity_date BETWEEN ? AND ?`
                : `al.activity_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)`;

            sql = `
                SELECT 
                    u.name as userName,
                    DATE_FORMAT(al.activity_date, '%Y-%m-%d') as label,
                    SUM(al.active_minutes) as value
                FROM users u
                JOIN user_activity_log al ON u.id = al.user_id
                WHERE ${whereClause} AND u.is_active = 1
                ${userId ? 'AND u.id = ?' : ''}
                GROUP BY u.id, al.activity_date
                ORDER BY al.activity_date ASC
            `;
            if (startDate && endDate) {
                params = userId ? [startDate, endDate, userId] : [startDate, endDate];
            } else if (userId) {
                params.push(userId);
            }
        } else {
            // Aggregation by Month for a specific range or last 12 months
            const whereClause = startDate && endDate
                ? `al.activity_date BETWEEN ? AND ?`
                : `al.activity_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)`;

            sql = `
                SELECT 
                    u.name as userName,
                    DATE_FORMAT(al.activity_date, '%Y-%m') as label,
                    SUM(al.active_minutes) as value
                FROM users u
                JOIN user_activity_log al ON u.id = al.user_id
                WHERE ${whereClause} AND u.is_active = 1
                ${userId ? 'AND u.id = ?' : ''}
                GROUP BY u.id, label
                ORDER BY label ASC
            `;
            if (startDate && endDate) {
                params = userId ? [startDate, endDate, userId] : [startDate, endDate];
            } else if (userId) {
                params.push(userId);
            }
        }

        const [chartRows] = await pool.execute(sql, params);
        const dataMap = {};
        if (range === 'day') {
            // Pre-fill all 24 hours (0 to 23)
            for (let i = 0; i < 24; i++) {
                dataMap[i] = { date: i };
            }
        }

        chartRows.forEach(row => {
            if (!dataMap[row.label]) {
                dataMap[row.label] = { date: row.label };
            }
            dataMap[row.label][row.userName] = parseFloat(Number(row.value).toFixed(2));
        });

        // Current Stats Summary logic
        let totalsSql = '';
        let totalParams = [];

        if (range === 'day' && (date || startDate)) {
            const targetDate = date || startDate || getISTDay();

            totalsSql = `
                SELECT u.id, u.name, IFNULL(SUM(al.active_minutes), 0) as totalMinutes, u.domain, 1 as activeDays, u.last_active_at, u.iactive,
                       TIMESTAMPDIFF(SECOND, u.last_active_at, NOW()) as secondsAgo
                FROM users u
                LEFT JOIN user_activity_log al ON u.id = al.user_id AND al.activity_date = ?
                WHERE u.is_active = 1
                ${userId ? 'AND u.id = ?' : ''}
                GROUP BY u.id
                ORDER BY IFNULL(SUM(al.active_minutes), 0) DESC
            `;
            totalParams = userId ? [targetDate, userId] : [targetDate];
        } else {
            const whereClause = startDate && endDate
                ? `al.activity_date BETWEEN ? AND ?`
                : `al.activity_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)`;

            const rangeDays = range === 'week' ? 7 : 30;

            totalsSql = `
                SELECT u.id, u.name, IFNULL(SUM(al.active_minutes), 0) as totalMinutes, u.domain, COUNT(DISTINCT al.activity_date) as activeDays, u.last_active_at, u.iactive,
                       TIMESTAMPDIFF(SECOND, u.last_active_at, NOW()) as secondsAgo
                FROM users u
                LEFT JOIN user_activity_log al ON u.id = al.user_id AND ${whereClause}
                WHERE u.is_active = 1
                ${userId ? 'AND u.id = ?' : ''}
                GROUP BY u.id
                ORDER BY IFNULL(SUM(al.active_minutes), 0) DESC
            `;

            if (startDate && endDate) {
                totalParams = userId ? [startDate, endDate, userId] : [startDate, endDate];
            } else {
                totalParams = userId ? [rangeDays, userId] : [rangeDays];
            }
        }

        const [totalsRows] = await pool.execute(totalsSql, totalParams);

        res.json({
            chartData: Object.values(dataMap),
            userTotals: totalsRows.map(u => ({
                id: u.id,
                name: u.name,
                domain: u.domain,
                last_active_at: u.last_active_at,
                iactive: u.iactive,
                secondsAgo: u.secondsAgo,
                totalMinutes: u.totalMinutes || 0,
                totalHours: parseFloat(((u.totalMinutes || 0) / 60).toFixed(2)),
                avgHours: parseFloat(((u.totalMinutes || 0) / 60 / (u.activeDays || 1)).toFixed(2))
            }))
        });
    } catch (err) {
        console.error("Efficiency API Error:", err);
        res.status(500).json({ error: err.message });
    }
});

// GET Granular Efficiency Report (Super Admin Only)
app.get('/api/admin/efficiency-report', authenticateToken, async (req, res) => {
    if (!isSuperAdminUser(req.user)) {
        return res.status(403).json({ msg: "Access denied. Super Admin only." });
    }

    try {
        const { startDate, endDate, userId } = req.query;
        if (!startDate || !endDate) {
            return res.status(400).json({ error: "startDate and endDate are required" });
        }

        let sql = `
            SELECT 
                al.activity_date, 
                al.user_id, 
                u.name as userName, 
                u.employee_id,
                u.domain,
                al.activity_hour, 
                al.active_minutes
            FROM user_activity_log al
            JOIN users u ON al.user_id = u.id
            WHERE al.activity_date BETWEEN ? AND ?
            AND u.is_active = 1
        `;
        const params = [startDate, endDate];

        if (userId && userId !== 'All') {
            sql += ` AND al.user_id = ?`;
            params.push(userId);
        }

        sql += ` ORDER BY al.activity_date ASC, u.name ASC, al.activity_hour ASC`;

        const [rows] = await pool.execute(sql, params);
        res.json(rows);
    } catch (err) {
        console.error("Efficiency Report API Error:", err);
        res.status(500).json({ error: err.message });
    }
});


// ================= META WEBHOOK LEAD INTEGRATION =================
const META_VERIFY_TOKEN = "mycrmverify123";
const META_ACCESS_TOKEN = "EAAeDhwLj4GYBRk1wsXhwRHqU7chqv9DmY4uZCyRYGNUYiwl9te6kJtWClQJLrLDIW3tz4wb7oI5HDw4VqieCGZAhYX9Q4scU69zxuZBZBCUIVCySeYDuMG0vH8lqejd1d3euaN3VxdygqdqCi41afl4ybCj7Gn2mGKVEHdV9aY1sVQlvjHKUAqeyHt6QnSLSa9SWuJvY";

const META_PAGE_DOMAIN_MAP = {
    "443339626098796": "Bluestone Overseas",
    "884912191368488": "Bluestone Techpark",
    "835486679651886": "Bluestone International Pre School",
    "902355596291561": "Bluestone Group of Institutions",
    "960652620453721": "Bluestone Placements",
    "798882103317956": "Bluestone Language Hub",
    "794545177065914": "Bluestone Elite Sports",
    "748860194967018": "Bluestoneiasacademy"
};

app.get('/meta-webhook', (req, res) => {
    const mode = req.query['hub.mode'];
    const token = req.query['hub.verify_token'];
    const challenge = req.query['hub.challenge'];

    if (mode === 'subscribe' && token === META_VERIFY_TOKEN) {
        console.log("Meta webhook verification successful via server.js!");
        return res.status(200).send(challenge);
    } else {
        console.warn(`Meta Webhook verification failed. Token mismatch.`);
        return res.sendStatus(403);
    }
});

app.post('/meta-webhook', async (req, res) => {
    console.log("🚀 DEBUG 1: Webhook route triggered raw body:", JSON.stringify(req.body));

    try {
        const change = req.body.entry?.[0]?.changes?.[0];
        const leadgen_id = change?.value?.leadgen_id;
        const page_id = change?.value?.page_id || req.body.entry?.[0]?.id;

        if (!leadgen_id) {
            console.log("❌ DEBUG 2: Dropped request because no leadgen_id was found in payload.");
            return res.sendStatus(200);
        }

        // 1. Dynamically find the target brand domain mapping based on incoming Page ID
        let targetDomain = "Bluestone Overseas"; // Default fallback
        if (page_id && META_PAGE_DOMAIN_MAP[page_id]) {
            targetDomain = META_PAGE_DOMAIN_MAP[page_id];
        }

        console.log(`✅ DEBUG 3: Extracted Leadgen ID: ${leadgen_id} | Dynamic Destination Brand: "${targetDomain}"`);

        // Fetch details from Meta Graph API
        console.log("🔄 DEBUG 4: Sending request to Meta Graph API...");
        const response = await axios.get(
            `https://graph.facebook.com/v25.0/${leadgen_id}`,
            { params: { access_token: META_ACCESS_TOKEN } }
        );
        console.log("✅ DEBUG 5: Meta Graph API Response received.");

        const fieldData = response.data.field_data;
        if (!fieldData) {
            console.warn("❌ DEBUG 6: Meta returned response but field_data array is completely missing.");
            return res.sendStatus(200);
        }

        let lead = {};
        fieldData.forEach(field => {
            lead[field.name] = field.values[0];
        });

        const finalLead = {
            name: lead.full_name || lead.name || 'Meta User',
            email: lead.email || '',
            phone: lead.phone_number || lead.phone || '',
            source: 'Facebook Ads'
        };
        console.log("📊 DEBUG 7: Flattened Lead object ready for database:", finalLead);

        console.log(`🔄 DEBUG 8: Getting next lead code sequence for dynamic brand: "${targetDomain}"...`);
        const leadCode = await getNextLeadCode(targetDomain); // FIXED: No longer hardcoded
        console.log(`✅ DEBUG 9: Lead Code assigned: ${leadCode}`);

        const nowStr = getISTString();
        const insertQuery = `
      INSERT INTO leads (student_name, email, phone, source, domain, status, lead_code, is_active, created_at)
      VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)
    `;

        console.log(`🔄 DEBUG 10: Executing Database Insert Query for brand: "${targetDomain}"...`);
        await pool.execute(insertQuery, [
            finalLead.name,
            finalLead.email,
            finalLead.phone,
            finalLead.source,
            targetDomain, // FIXED: No longer hardcoded
            'New',
            leadCode,
            nowStr
        ]);

        console.log("🎉 DEBUG 11: SUCCESS! Database write confirmed.");

        // Trigger dynamic notifications
        triggerEmails(finalLead.name, finalLead.email, finalLead.phone, 'Meta Lead Ads Enquiry', targetDomain, 'Meta Lead Ads', 'Facebook Ads');

        res.sendStatus(200);

    } catch (error) {
        console.error("🚨 CRITICAL BREAKDOWN LOGGED:");
        if (error.response) {
            console.error("Meta Graph API Server Error Details:", error.response.data);
        } else {
            console.error("Local Script/Database Error Message:", error.message);
        }
        res.sendStatus(200); // Keep sending 200 to Meta so they don't break your webhook connection stream
    }
});

// ==========================================
// ASSET MANAGEMENT APIs
// ==========================================

// GET all assets with assigned user details
app.get('/api/assets', authenticateToken, async (req, res) => {
    try {
        const query = `
            SELECT a.*, u.name as assigned_to_name
            FROM assets a
            LEFT JOIN users u ON a.assigned_to = u.id
            ORDER BY a.created_at DESC
        `;
        const [rows] = await pool.execute(query);
        res.json(rows);
    } catch (err) {
        console.error("Error fetching assets:", err);
        res.status(500).json({ msg: "Failed to fetch assets" });
    }
});

// POST new asset
app.post('/api/assets', authenticateToken, async (req, res) => {
    try {
        const { category, asset_name, brand, model, login_username, login_password, approx_value, assigned_to, custom_assigned_name, has_charger } = req.body;

        if (!category) return res.status(400).json({ msg: "Category is required" });

        const query = `
            INSERT INTO assets (category, asset_name, brand, model, login_username, login_password, approx_value, assigned_to, custom_assigned_name, has_charger)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        `;

        const params = [
            category,
            asset_name || null,
            brand || null,
            model || null,
            login_username || null,
            login_password || null,
            approx_value || null,
            assigned_to || null,
            custom_assigned_name || null,
            has_charger ? 1 : 0
        ];

        const [result] = await pool.execute(query, params);
        const newId = result.insertId;

        // Log history
        await pool.execute(
            "INSERT INTO asset_history (asset_id, action_type, changed_by, change_details) VALUES (?, ?, ?, ?)",
            [newId, 'CREATED', req.user.id, `Asset created in category: ${category}`]
        );

        res.status(201).json({ msg: "Asset added successfully" });
    } catch (err) {
        console.error("Error adding asset:", err);
        res.status(500).json({ msg: "Failed to add asset" });
    }
});

// PUT update asset
app.put('/api/assets/:id', authenticateToken, async (req, res) => {
    try {
        const { id } = req.params;
        const { category, asset_name, brand, model, login_username, login_password, approx_value, assigned_to, custom_assigned_name, has_charger } = req.body;

        // Fetch old values for history comparison
        const [oldRows] = await pool.execute("SELECT * FROM assets WHERE id = ?", [id]);
        if (oldRows.length === 0) return res.status(404).json({ msg: "Asset not found" });
        const old = oldRows[0];

        // Compare fields to detect specific changes
        let changes = [];
        const compare = (field, label, newVal, oldVal) => {
            // Trim and normalize values
            const nV = (newVal === undefined || newVal === null) ? "" : String(newVal).trim();
            const oV = (oldVal === undefined || oldVal === null) ? "" : String(oldVal).trim();

            // Special handling for booleans/bit fields
            const finalNV = (field === 'has_charger') ? (newVal ? 1 : 0) : nV;
            const finalOV = (field === 'has_charger') ? (oldVal ? 1 : 0) : oV;

            if (String(finalNV) !== String(finalOV)) {
                // For numeric values, compare as numbers to avoid .00 issues
                if (field === 'approx_value') {
                    if (Number(newVal) === Number(oldVal)) return;
                }

                const from = (field === 'has_charger') ? (finalOV ? 'Yes' : 'No') : (oldVal || 'N/A');
                const to = (field === 'has_charger') ? (finalNV ? 'Yes' : 'No') : (newVal || 'N/A');
                changes.push(`${label}: ${from} ➔ ${to}`);
            }
        };

        compare('category', 'Category', category, old.category);
        compare('brand', 'Brand', brand, old.brand);
        compare('model', 'Model', model, old.model);
        compare('asset_name', 'Item Name', asset_name, old.asset_name);
        compare('login_username', 'Login User', login_username, old.login_username);
        compare('approx_value', 'Value', approx_value, old.approx_value);
        compare('custom_assigned_name', 'Assigned Name', custom_assigned_name, old.custom_assigned_name);
        compare('has_charger', 'Charger', has_charger, old.has_charger);

        // Handle staff assignment with names
        if (String(assigned_to || "") !== String(old.assigned_to || "")) {
            let oldStaffName = "Unassigned";
            let newStaffName = "Unassigned";

            if (old.assigned_to) {
                const [u] = await pool.execute("SELECT name FROM users WHERE id = ?", [old.assigned_to]);
                if (u.length > 0) oldStaffName = u[0].name;
            }
            if (assigned_to) {
                const [u] = await pool.execute("SELECT name FROM users WHERE id = ?", [assigned_to]);
                if (u.length > 0) newStaffName = u[0].name;
            }
            changes.push(`Assigned Staff: ${oldStaffName} ➔ ${newStaffName}`);
        }

        const changeMessage = changes.length > 0 ? changes.join(" | ") : "Saved without changes";

        const query = `
            UPDATE assets 
            SET category = ?, asset_name = ?, brand = ?, model = ?, login_username = ?, login_password = ?, approx_value = ?, assigned_to = ?, custom_assigned_name = ?, has_charger = ?
            WHERE id = ?
        `;

        const params = [
            category,
            asset_name || null,
            brand || null,
            model || null,
            login_username || null,
            login_password || null,
            approx_value || null,
            assigned_to || null,
            custom_assigned_name || null,
            has_charger ? 1 : 0,
            id
        ];

        await pool.execute(query, params);

        // Log detailed history
        await pool.execute(
            "INSERT INTO asset_history (asset_id, action_type, changed_by, change_details) VALUES (?, ?, ?, ?)",
            [id, 'UPDATED', req.user.id, changeMessage]
        );

        res.json({ msg: "Asset updated successfully" });
    } catch (err) {
        console.error("Error updating asset:", err);
        res.status(500).json({ msg: "Failed to update asset" });
    }
});

// DELETE asset
app.delete('/api/assets/:id', authenticateToken, async (req, res) => {
    try {
        const { id } = req.params;
        await pool.execute("DELETE FROM assets WHERE id = ?", [id]);
        res.json({ msg: "Asset deleted successfully" });
    } catch (err) {
        console.error("Error deleting asset:", err);
        res.status(500).json({ msg: "Failed to delete asset" });
    }
});

// GET asset history
app.get('/api/assets/:id/history', authenticateToken, async (req, res) => {
    try {
        const { id } = req.params;
        const query = `
            SELECT h.*, u.name as user_name
            FROM asset_history h
            LEFT JOIN users u ON h.changed_by = u.id
            WHERE h.asset_id = ?
            ORDER BY h.created_at DESC
        `;
        const [rows] = await pool.execute(query, [id]);
        res.json(rows);
    } catch (err) {
        console.error("Error fetching asset history:", err);
        res.status(500).json({ msg: "Failed to fetch history" });
    }
});

// GET all learning countries
app.get('/api/learning-countries', authenticateToken, async (req, res) => {
    try {
        const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
        let query = 'SELECT * FROM learning_countries';
        // Non-super-admins only see active countries
        if (!isSuperAdmin) {
            query += ' WHERE is_active = 1';
        }
        query += ' ORDER BY name ASC';
        const [rows] = await pool.execute(query);
        res.json(rows);
    } catch (err) {
        console.error("Fetch countries error:", err);
        res.status(500).json({ error: "Failed to fetch countries" });
    }
});

// POST add learning country (Super Admin only)
app.post('/api/learning-countries', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can add countries." });
    }

    const { code, name, flag } = req.body;
    if (!code || !name || !flag) {
        return res.status(400).json({ msg: "Missing code, name, or flag" });
    }

    try {
        const normalizedCode = String(code).trim().toLowerCase().replace(/\s+/g, '-');
        await pool.execute(
            'INSERT INTO learning_countries (code, name, flag) VALUES (?, ?, ?)',
            [normalizedCode, name.trim(), flag.trim()]
        );
        res.status(201).json({ msg: "Country added successfully!" });
    } catch (err) {
        if (err.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ msg: "A country with this code already exists." });
        }
        console.error("Add country error:", err);
        res.status(500).json({ error: "Failed to add country" });
    }
});

// DELETE learning country (Super Admin only)
app.delete('/api/learning-countries/:id', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can delete countries." });
    }

    const { id } = req.params;
    try {
        await pool.execute('DELETE FROM learning_countries WHERE id = ?', [id]);
        res.json({ msg: "Country deleted successfully!" });
    } catch (err) {
        console.error("Delete country error:", err);
        res.status(500).json({ error: "Failed to delete country" });
    }
});

// PATCH toggle learning country is_active (Super Admin only)
app.patch('/api/learning-countries/:id/toggle', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can toggle country visibility." });
    }

    const { id } = req.params;
    try {
        // Get current state and flip it
        const [rows] = await pool.execute('SELECT is_active FROM learning_countries WHERE id = ?', [id]);
        if (rows.length === 0) return res.status(404).json({ msg: "Country not found" });

        const newValue = rows[0].is_active === 1 ? 0 : 1;
        await pool.execute('UPDATE learning_countries SET is_active = ? WHERE id = ?', [newValue, id]);
        res.json({ msg: "Country visibility toggled successfully!", is_active: newValue });
    } catch (err) {
        console.error("Toggle country error:", err);
        res.status(500).json({ error: "Failed to toggle country visibility" });
    }
});

// GET all learning categories (optionally isolated by country_code)
app.get('/api/learning-categories', authenticateToken, async (req, res) => {
    const { country_code } = req.query;
    try {
        let rows;
        if (country_code) {
            [rows] = await pool.execute(
                'SELECT * FROM learning_categories WHERE country_code = ? OR country_code IS NULL ORDER BY id ASC',
                [country_code]
            );
        } else {
            [rows] = await pool.execute('SELECT * FROM learning_categories ORDER BY id ASC');
        }
        res.json(rows);
    } catch (err) {
        console.error("Fetch categories error:", err);
        res.status(500).json({ error: "Failed to fetch categories" });
    }
});

// POST add learning category (Super Admin only, specific to country_code)
app.post('/api/learning-categories', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can add categories." });
    }

    const { name, country_code } = req.body;
    if (!name) {
        return res.status(400).json({ msg: "Category name is required" });
    }

    try {
        const code = String(name).trim().toLowerCase().replace(/\s+/g, '-');
        await pool.execute(
            'INSERT INTO learning_categories (code, name, country_code) VALUES (?, ?, ?)',
            [code, name.trim(), country_code || null]
        );
        res.status(201).json({ msg: "Category added successfully!" });
    } catch (err) {
        if (err.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ msg: "A category folder with this name already exists." });
        }
        console.error("Add category error:", err);
        res.status(500).json({ error: "Failed to add category" });
    }
});

// DELETE learning category (Super Admin only)
app.delete('/api/learning-categories/:id', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can delete categories." });
    }

    const { id } = req.params;
    try {
        await pool.execute('DELETE FROM learning_categories WHERE id = ?', [id]);
        res.json({ msg: "Category deleted successfully!" });
    } catch (err) {
        console.error("Delete category error:", err);
        res.status(500).json({ error: "Failed to delete category" });
    }
});

// GET all learning resources
app.get('/api/learning-resources', authenticateToken, async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM learning_resources ORDER BY id DESC');
        res.json(rows);
    } catch (err) {
        console.error("Fetch learning resources error:", err);
        res.status(500).json({ error: "Failed to fetch learning resources" });
    }
});

// POST upload new learning resource (Super Admin only)
app.post('/api/learning-resources/upload', authenticateToken, (req, res) => {
    // Wrap multer in custom handler to catch errors gracefully
    const uploadMultiple = uploadDocuments.any();
    uploadMultiple(req, res, async function (err) {
        if (err) {
            console.error("❌ Multer upload error caught:", err);
        }
        if (err instanceof multer.MulterError) {
            return res.status(400).json({ msg: `Upload error: ${err.message}` });
        } else if (err) {
            return res.status(400).json({ msg: `Upload error: ${err.message}` });
        }

        console.log("📝 Upload endpoint request body:", req.body);
        console.log("📂 Upload endpoint request files count:", req.files ? req.files.length : 0);

        // Security: Only Super Admins can upload resources
        const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
        if (!isSuperAdmin) {
            return res.status(403).json({ msg: "Only Super Admins can upload learning resources." });
        }

        const { country_id, category_id } = req.body;
        if (!country_id || !category_id) {
            console.warn("⚠️ Upload failed: Missing country_id or category_id in body:", req.body);
            return res.status(400).json({ msg: "Missing required fields (country_id, category_id)" });
        }

        if (!req.files || req.files.length === 0) {
            console.warn("⚠️ Upload failed: No files found in req.files");
            return res.status(400).json({ msg: "Please select files to upload" });
        }

        // Parse custom titles if sent from front-end
        let titles = [];
        if (req.body.titles) {
            try {
                titles = JSON.parse(req.body.titles);
            } catch (e) {
                if (typeof req.body.titles === 'string') {
                    titles = [req.body.titles];
                }
            }
        }

        try {
            const dateStr = getISTString().split(' ')[0]; // Returns YYYY-MM-DD
            const [y, m, d] = dateStr.split('-');
            const formattedDate = `${d}-${m}-${y}`;

            for (let idx = 0; idx < req.files.length; idx++) {
                const file = req.files[idx];
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2) + " MB";
                const filePath = `uploads/documents/${file.filename}`;

                // Get title for this file
                let finalTitle = titles[idx] || file.originalname;
                // remove file extension from title if using originalname
                if (!titles[idx] || titles[idx] === file.originalname) {
                    const extIndex = file.originalname.lastIndexOf('.');
                    if (extIndex > 0) {
                        finalTitle = file.originalname.substring(0, extIndex);
                    }
                }

                await pool.execute(
                    'INSERT INTO learning_resources (country_id, category_id, file_name, file_size, file_date, file_path) VALUES (?, ?, ?, ?, ?, ?)',
                    [country_id, category_id, finalTitle, sizeInMB, formattedDate, filePath]
                );
            }

            res.status(201).json({ msg: "Documents uploaded successfully!" });
        } catch (dbErr) {
            console.error("DB Save Error:", dbErr);
            res.status(500).json({ error: "Failed to save learning resources to database" });
        }
    });
});

// DELETE learning resource (Super Admin only)
app.delete('/api/learning-resources/:id', authenticateToken, async (req, res) => {
    // Security: Only Super Admins can delete resources
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can delete learning resources." });
    }

    const { id } = req.params;
    try {
        const [rows] = await pool.execute('SELECT file_path FROM learning_resources WHERE id = ?', [id]);
        if (rows.length === 0) return res.status(404).json({ msg: "Document not found" });

        const file_path = rows[0].file_path;
        if (file_path) {
            const absolutePath = path.join(__dirname, file_path);
            if (fs.existsSync(absolutePath)) {
                fs.unlinkSync(absolutePath);
            }
        }

        await pool.execute('DELETE FROM learning_resources WHERE id = ?', [id]);
        res.json({ msg: "Document deleted successfully!" });
    } catch (err) {
        console.error("Delete learning resource error:", err);
        res.status(500).json({ error: "Failed to delete learning resource" });
    }
});

// PUT update learning country (Super Admin only)
app.put('/api/learning-countries/:id', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can edit countries." });
    }

    const { id } = req.params;
    const { code, name, flag } = req.body;
    if (!code || !name || !flag) {
        return res.status(400).json({ msg: "Missing code, name, or flag" });
    }

    const connection = await pool.getConnection();
    try {
        await connection.beginTransaction();

        // Get old code first to update references
        const [oldRows] = await connection.execute('SELECT code FROM learning_countries WHERE id = ?', [id]);
        if (oldRows.length === 0) {
            await connection.rollback();
            return res.status(404).json({ msg: "Country not found" });
        }
        const oldCode = oldRows[0].code;
        const newCode = String(code).trim().toLowerCase().replace(/\s+/g, '-');

        // Update country
        await connection.execute(
            'UPDATE learning_countries SET code = ?, name = ?, flag = ? WHERE id = ?',
            [newCode, name.trim(), flag.trim(), id]
        );

        // Update references if code changed
        if (oldCode !== newCode) {
            await connection.execute(
                'UPDATE learning_categories SET country_code = ? WHERE country_code = ?',
                [newCode, oldCode]
            );
            await connection.execute(
                'UPDATE learning_resources SET country_id = ? WHERE country_id = ?',
                [newCode, oldCode]
            );
        }

        await connection.commit();
        res.json({ msg: "Country updated successfully!" });
    } catch (err) {
        await connection.rollback();
        if (err.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ msg: "A country with this code already exists." });
        }
        console.error("Update country error:", err);
        res.status(500).json({ error: "Failed to update country" });
    } finally {
        connection.release();
    }
});

// PUT update learning category (Super Admin only)
app.put('/api/learning-categories/:id', authenticateToken, async (req, res) => {
    const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
    if (!isSuperAdmin) {
        return res.status(403).json({ msg: "Only Super Admins can edit categories." });
    }

    const { id } = req.params;
    const { name } = req.body;
    if (!name) {
        return res.status(400).json({ msg: "Category name is required" });
    }

    const connection = await pool.getConnection();
    try {
        await connection.beginTransaction();

        // Get old category details first
        const [oldRows] = await connection.execute('SELECT code, country_code FROM learning_categories WHERE id = ?', [id]);
        if (oldRows.length === 0) {
            await connection.rollback();
            return res.status(404).json({ msg: "Category not found" });
        }
        const oldCode = oldRows[0].code;
        const countryCode = oldRows[0].country_code;
        const newCode = String(name).trim().toLowerCase().replace(/\s+/g, '-');

        // Update category
        await connection.execute(
            'UPDATE learning_categories SET code = ?, name = ? WHERE id = ?',
            [newCode, name.trim(), id]
        );

        // Update references if code changed
        if (oldCode !== newCode) {
            await connection.execute(
                'UPDATE learning_resources SET category_id = ? WHERE category_id = ? AND country_id = ?',
                [newCode, oldCode, countryCode]
            );
        }

        await connection.commit();
        res.json({ msg: "Category updated successfully!" });
    } catch (err) {
        await connection.rollback();
        if (err.code === 'ER_DUP_ENTRY') {
            return res.status(400).json({ msg: "A category folder with this name already exists." });
        }
        console.error("Update category error:", err);
        res.status(500).json({ error: "Failed to update category" });
    } finally {
        connection.release();
    }
});

// PUT update learning resource/document (Super Admin only - supports replacing title & file)
app.put('/api/learning-resources/:id', authenticateToken, (req, res) => {
    const uploadSingle = uploadDocuments.single('file');
    uploadSingle(req, res, async function (err) {
        if (err) {
            console.error("❌ Multer upload error during document edit:", err);
            return res.status(400).json({ msg: `Upload error: ${err.message}` });
        }

        const isSuperAdmin = req.user.tier === 'SUPER_ADMIN' || ['Main Admin', 'MD', 'GM', 'Super Admin'].includes(req.user.role);
        if (!isSuperAdmin) {
            return res.status(403).json({ msg: "Only Super Admins can edit learning resources." });
        }

        const { id } = req.params;
        const { file_name } = req.body;
        const hasNewFile = Boolean(req.file);

        try {
            // Fetch old record first to see if we need to delete old file
            const [oldRows] = await pool.execute('SELECT file_path, file_name FROM learning_resources WHERE id = ?', [id]);
            if (oldRows.length === 0) {
                return res.status(404).json({ msg: "Document not found" });
            }

            const oldFilePath = oldRows[0].file_path;

            // If a new file is uploaded, process it
            if (hasNewFile) {
                const file = req.file;
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2) + " MB";
                const filePath = `uploads/documents/${file.filename}`;

                const dateStr = getISTString().split(' ')[0]; // Returns YYYY-MM-DD
                const [y, m, d] = dateStr.split('-');
                const formattedDate = `${d}-${m}-${y}`;

                // Determine final title: use the new file name if a new one is not explicitly provided in file_name
                let finalTitle = (file_name || '').trim();
                if (!finalTitle) {
                    const extIndex = file.originalname.lastIndexOf('.');
                    if (extIndex > 0) {
                        finalTitle = file.originalname.substring(0, extIndex);
                    } else {
                        finalTitle = file.originalname;
                    }
                }

                // Delete old file from disk
                if (oldFilePath) {
                    const absolutePath = path.join(__dirname, oldFilePath);
                    if (fs.existsSync(absolutePath)) {
                        fs.unlinkSync(absolutePath);
                    }
                }

                // Update database with new file details
                await pool.execute(
                    'UPDATE learning_resources SET file_name = ?, file_size = ?, file_date = ?, file_path = ? WHERE id = ?',
                    [finalTitle, sizeInMB, formattedDate, filePath, id]
                );
            } else {
                // If only editing the title
                let finalTitle = (file_name || '').trim();
                if (!finalTitle) {
                    return res.status(400).json({ msg: "Document title is required" });
                }

                await pool.execute(
                    'UPDATE learning_resources SET file_name = ? WHERE id = ?',
                    [finalTitle, id]
                );
            }

            res.json({ msg: "Document updated successfully!" });
        } catch (dbErr) {
            console.error("DB Edit Error:", dbErr);
            res.status(500).json({ error: "Failed to update learning resource details" });
        }
    });
});


// --- NEW: OVERSEAS PIPELINE ENDPOINTS ---
app.get('/api/overseas-pipeline', authenticateToken, async (req, res) => {
    try {
        const { role, domain: userDomain, id: loggedUserId } = req.user;
        const isSuperAdmin = isSuperAdminUser(req.user);

        let sql = `SELECT * FROM leads WHERE is_active = 1 AND status = 'Enrolled'`;
        const params = [];

        // Apply domain filter for Overseas
        sql = appendDomainCondition(sql, params, 'Overseas');

        // Apply user constraints
        if (!isSuperAdmin) {
            // Must have Overseas in their domain access
            const authorized = String(userDomain || '').split(',').map(d => d.trim().toLowerCase());
            if (!authorized.some(d => d.includes('overseas') || d === 'all')) {
                return res.status(403).json({ error: "Access denied. You do not have access to the Overseas domain." });
            }
        }

        // Apply staff filter
        if (isStaffUser(req.user)) {
            sql += " AND assigned_to = ?";
            params.push(loggedUserId);
        }

        // Apply company filter
        const loggedUserType = req.user.user_type || 'CORE';
        if (loggedUserType === 'AGENT' || loggedUserType === 'FRANCHISE') {
            sql += ' AND company_name = ?';
            params.push(req.user.company_name);
        }

        sql += " ORDER BY created_at DESC";

        const [rows] = await pool.execute(sql, params);
        res.json({ success: true, data: rows });
    } catch (err) {
        console.error("Pipeline Fetch Error:", err);
        res.status(500).json({ error: "Failed to fetch pipeline leads" });
    }
});

app.put('/api/leads/:id/pipeline-status', authenticateToken, async (req, res) => {
    try {
        const leadId = req.params.id;
        const { status } = req.body;

        const allowedStatuses = [
            'Document Collection',
            'University Applied',
            'Offer Received',
            'CAS / I-20 Processing',
            'Visa Processing',
            'Visa Approved',
            'Completed'
        ];

        if (!allowedStatuses.includes(status)) {
            return res.status(400).json({ error: "Invalid pipeline status" });
        }

        // Also check if user has edit permission
        if (req.user.can_edit === 0) {
            return res.status(403).json({ error: "You don't have permission to edit leads." });
        }

        const finalStatus = status === 'Completed' ? 'Closed' : 'Enrolled';
        const [beforeRows] = await pool.execute(
            'SELECT status, overseas_pipeline_status FROM leads WHERE id = ?',
            [leadId]
        );
        if (beforeRows.length === 0) {
            return res.status(404).json({ error: "Lead not found" });
        }
        const before = beforeRows[0];
        await pool.execute(
            'UPDATE leads SET overseas_pipeline_status = ?, status = ? WHERE id = ?',
            [status, finalStatus, leadId]
        );

        const changedBy = req.user?.name || 'System';
        await recordLeadEditHistory(leadId, 'overseas_pipeline_status', before.overseas_pipeline_status, status, changedBy, 'PIPELINE_UPDATE');
        await recordLeadEditHistory(leadId, 'status', before.status, finalStatus, changedBy, 'PIPELINE_UPDATE');

        res.json({ success: true, message: "Pipeline status updated successfully" });
    } catch (err) {
        console.error("Pipeline Status Update Error:", err);
        res.status(500).json({ error: "Failed to update pipeline status" });
    }
});

app.post('/api/leads/bulk-pipeline-status', authenticateToken, async (req, res) => {
    try {
        const { leadIds, status, university_name, course_name, remarks } = req.body;
        if (!leadIds || !Array.isArray(leadIds) || leadIds.length === 0) {
            return res.status(400).json({ error: "No leads selected" });
        }

        const allowedStatuses = [
            'Document Collection',
            'University Applied',
            'Offer Received',
            'CAS / I-20 Processing',
            'Visa Processing',
            'Visa Approved',
            'Completed'
        ];

        if (!allowedStatuses.includes(status)) {
            return res.status(400).json({ error: "Invalid pipeline status" });
        }

        // Also check if user has edit permission
        if (req.user.can_edit === 0) {
            return res.status(403).json({ error: "You don't have permission to edit leads." });
        }

        const placeholders = leadIds.map(() => '?').join(',');

        const finalStatus = status === 'Completed' ? 'Closed' : 'Enrolled';
        const [beforeRows] = await pool.execute(
            `SELECT id, status, overseas_pipeline_status, university_name, course_name, remarks
             FROM leads WHERE id IN (${placeholders})`,
            leadIds
        );

        let updateSql = `UPDATE leads SET overseas_pipeline_status = ?, status = ?`;
        const params = [status, finalStatus];

        if (university_name) {
            updateSql += `, university_name = ?`;
            params.push(university_name);
        }
        if (course_name) {
            updateSql += `, course_name = ?`;
            params.push(course_name);
        }
        if (remarks) {
            updateSql += `, remarks = CONCAT(IFNULL(remarks, ''), '\n[Pipeline Update]: ', ?)`;
            params.push(remarks);
        }

        updateSql += ` WHERE id IN (${placeholders})`;
        params.push(...leadIds);

        await pool.execute(updateSql, params);

        const changedBy = req.user?.name || 'System';
        for (const lead of beforeRows) {
            await recordLeadEditHistory(lead.id, 'overseas_pipeline_status', lead.overseas_pipeline_status, status, changedBy, 'PIPELINE_UPDATE');
            await recordLeadEditHistory(lead.id, 'status', lead.status, finalStatus, changedBy, 'PIPELINE_UPDATE');
            if (university_name !== undefined) {
                await recordLeadEditHistory(lead.id, 'university_name', lead.university_name, university_name, changedBy, 'PIPELINE_UPDATE');
            }
            if (course_name !== undefined) {
                await recordLeadEditHistory(lead.id, 'course_name', lead.course_name, course_name, changedBy, 'PIPELINE_UPDATE');
            }
            if (remarks) {
                await recordLeadEditHistory(
                    lead.id,
                    'remarks',
                    lead.remarks,
                    `${lead.remarks || ''}\n[Pipeline Update]: ${remarks}`,
                    changedBy,
                    'PIPELINE_UPDATE'
                );
            }
        }

        res.json({ success: true, message: "Pipeline statuses updated successfully" });
    } catch (err) {
        console.error("Bulk Pipeline Status Update Error:", err);
        res.status(500).json({ error: "Failed to bulk update pipeline status" });
    }
});

const startServer = async () => {
    try {
        await initDB();
        const PORT = 5005;
        app.listen(PORT, '0.0.0.0', () => {
            console.log(`🚀 Bluestone CRM Backend active on port ${PORT}`);

            // 🕒 INACTIVITY SWEEPER: Every 60 seconds, mark users as inactive if no click/ping
            setInterval(async () => {
                try {
                    await pool.execute(`
                        UPDATE users 
                        SET iactive = 0 
                        WHERE iactive = 1 
                        AND last_active_at < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 MINUTE)
                    `);
                } catch (err) {
                    console.error("[Sweeper Error]:", err.message);
                }
            }, 60000);
        });
    } catch (err) {
        console.error("Critical Startup Error:", err.message);
        process.exit(1);
    }
};

startServer();
