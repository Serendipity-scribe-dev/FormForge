<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormForge — AI Form Generator</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #ffffff; --sidebar: #f7f7f5; --border: #e8e8e4;
            --text-primary: #1a1a18; --text-secondary: #6b6b63; --text-muted: #9e9e96;
            --accent: #2a5cff; --accent-light: #eef1ff;
            --user-bubble: #1a1a18; --user-text: #ffffff;
            --bot-bubble: #f7f7f5; --bot-text: #1a1a18;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            --radius: 14px;
        }
        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text-primary); line-height: 1.6; }
        .app { display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: 260px; min-width: 260px; background: var(--sidebar); border-right: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-header { padding: 24px 20px 16px; border-bottom: 1px solid var(--border); }
        .logo { font-family: 'Instrument Serif', serif; font-size: 20px; color: var(--text-primary); }
        .logo span { color: var(--accent); }
        .logo-sub { font-size: 11px; color: var(--text-muted); font-weight: 400; margin-top: 2px; letter-spacing: 0.3px; text-transform: uppercase; }
        .new-chat-btn { margin: 16px 20px; padding: 10px 16px; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; cursor: pointer; font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 500; color: var(--text-primary); display: flex; align-items: center; gap: 8px; transition: all 0.15s; box-shadow: var(--shadow); }
        .new-chat-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
        .sidebar-section-label { padding: 8px 20px 6px; font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); }
        .history-list { flex: 1; overflow-y: auto; padding: 0 10px 20px; }
        .history-list::-webkit-scrollbar { width: 4px; }
        .history-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .history-item { padding: 9px 12px; border-radius: 9px; cursor: pointer; font-size: 13px; color: var(--text-secondary); transition: all 0.12s; display: flex; align-items: center; gap: 8px; overflow: hidden; }
        .history-item:hover { background: var(--bg); color: var(--text-primary); }
        .history-item.active { background: var(--bg); color: var(--text-primary); font-weight: 500; box-shadow: var(--shadow); }
        .history-item .item-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Main */
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { padding: 16px 28px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; min-height: 60px; }
        .chat-title { font-family: 'Instrument Serif', serif; font-size: 17px; color: var(--text-primary); }
        .model-badge { font-size: 11.5px; font-weight: 500; color: var(--text-muted); background: var(--sidebar); border: 1px solid var(--border); padding: 4px 10px; border-radius: 20px; display: flex; align-items: center; gap: 5px; }
        .model-badge::before { content: ''; width: 6px; height: 6px; background: #22c55e; border-radius: 50%; flex-shrink: 0; }

        /* Messages */
        .messages-wrap { flex: 1; overflow-y: auto; padding: 32px 0; }
        .messages-wrap::-webkit-scrollbar { width: 4px; }
        .messages-wrap::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .messages-inner { max-width: 720px; margin: 0 auto; padding: 0 28px; display: flex; flex-direction: column; gap: 28px; }

        .empty-state { max-width: 720px; margin: 60px auto 0; padding: 0 28px; text-align: center; }
        .empty-icon { font-size: 42px; margin-bottom: 16px; }
        .empty-state h2 { font-family: 'Instrument Serif', serif; font-size: 28px; margin-bottom: 8px; }
        .empty-state p { font-size: 14.5px; color: var(--text-secondary); max-width: 380px; margin: 0 auto 32px; }
        .suggestions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; max-width: 520px; margin: 0 auto; }
        .suggestion-card { background: var(--sidebar); border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px; cursor: pointer; text-align: left; transition: all 0.15s; font-family: 'DM Sans', sans-serif; }
        .suggestion-card:hover { border-color: var(--accent); background: var(--accent-light); }
        .suggestion-card .s-label { font-size: 13px; font-weight: 500; color: var(--text-primary); margin-bottom: 3px; }
        .suggestion-card .s-sub { font-size: 12px; color: var(--text-muted); }

        .msg-row { display: flex; gap: 12px; align-items: flex-start; animation: fadeUp 0.25s ease forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .msg-row.user { flex-direction: row-reverse; }
        .avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-weight: 600; }
        .avatar.bot { background: var(--accent-light); color: var(--accent); font-family: 'Instrument Serif', serif; font-size: 16px; }
        .avatar.user-av { background: var(--user-bubble); color: var(--user-text); font-size: 12px; }
        .bubble { max-width: 84%; padding: 13px 17px; border-radius: var(--radius); font-size: 14.5px; line-height: 1.65; }
        .msg-row.bot .bubble { background: var(--bot-bubble); color: var(--bot-text); border: 1px solid var(--border); border-top-left-radius: 4px; }
        .msg-row.user .bubble { background: var(--user-bubble); color: var(--user-text); border-top-right-radius: 4px; }

        /* Result card */
        .result-card { margin-top: 14px; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .result-card-header { padding: 10px 14px; background: var(--sidebar); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; }
        .result-card-body { padding: 14px; display: flex; gap: 10px; flex-wrap: wrap; }
        .download-btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px; border-radius: 9px; font-family: 'DM Sans', sans-serif; font-size: 13.5px; font-weight: 500; text-decoration: none; border: none; cursor: pointer; transition: all 0.15s; }
        .download-btn.primary { background: var(--accent); color: #fff; }
        .download-btn.primary:hover { background: #000dff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(42,92,255,0.25); }
        .download-btn.secondary { background: var(--sidebar); color: var(--text-primary); border: 1px solid var(--border); }
        .download-btn.secondary:hover { background: var(--border); }

        /* Folder info */
        .folder-info { margin-top: 10px; font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
        .folder-info code { background: var(--sidebar); border: 1px solid var(--border); border-radius: 4px; padding: 1px 6px; font-size: 11.5px; font-family: monospace; color: var(--text-secondary); }

        /* Setup accordion */
        .setup-card { margin-top: 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .setup-toggle { width: 100%; padding: 11px 14px; background: #fffbf0; border: none; border-bottom: 1px solid var(--border); cursor: pointer; font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between; transition: background 0.15s; }
        .setup-toggle:hover { background: #fef3c7; }
        .setup-chevron { transition: transform 0.2s; display: inline-block; }
        .setup-toggle.open .setup-chevron { transform: rotate(180deg); }
        .setup-body { display: none; padding: 14px 16px; font-size: 13.5px; line-height: 1.8; color: var(--text-secondary); white-space: pre-wrap; }
        .setup-body.visible { display: block; }

        /* Typing */
        .typing-dots { display: flex; gap: 5px; padding: 14px 17px; background: var(--bot-bubble); border: 1px solid var(--border); border-radius: var(--radius); border-top-left-radius: 4px; }
        .typing-dots span { width: 7px; height: 7px; background: var(--text-muted); border-radius: 50%; animation: bounce 1.2s infinite; }
        .typing-dots span:nth-child(2) { animation-delay: 0.15s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.3s; }
        @keyframes bounce { 0%,60%,100% { transform: translateY(0); opacity: .4; } 30% { transform: translateY(-5px); opacity: 1; } }

        .error-msg { color: #dc2626; font-size: 13.5px; padding: 10px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 9px; margin-top: 10px; }

        /* Input */
        .input-area { padding: 20px 28px 24px; border-top: 1px solid var(--border); background: var(--bg); }
        .input-box { max-width: 720px; margin: 0 auto; background: var(--sidebar); border: 1.5px solid var(--border); border-radius: 14px; display: flex; align-items: flex-end; gap: 8px; padding: 10px 10px 10px 16px; transition: border-color 0.15s, box-shadow 0.15s; }
        .input-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(42,92,255,0.08); background: var(--bg); }
        #prompt-input { flex: 1; background: transparent; border: none; outline: none; font-family: 'DM Sans', sans-serif; font-size: 14.5px; color: var(--text-primary); resize: none; max-height: 160px; min-height: 22px; line-height: 1.6; padding: 3px 0; }
        #prompt-input::placeholder { color: var(--text-muted); }
        .send-btn { width: 38px; height: 38px; background: var(--accent); border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.15s; color: #fff; }
        .send-btn:hover:not(:disabled) { background: #1a4aee; transform: scale(1.05); }
        .send-btn:disabled { background: var(--border); cursor: not-allowed; }
        .input-hint { max-width: 720px; margin: 8px auto 0; font-size: 11.5px; color: var(--text-muted); text-align: center; }

        @media (max-width: 640px) {
            .sidebar { display: none; }
            .topbar, .input-area { padding-left: 16px; padding-right: 16px; }
            .messages-inner { padding: 0 16px; }
        }
    </style>
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">Form<span>Forge</span></div>
            <div class="logo-sub">AI Form Generator</div>
        </div>
        <button class="new-chat-btn" onclick="newChat()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            New conversation
        </button>
        <div class="sidebar-section-label">History</div>
        <div class="history-list" id="history-list"></div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div class="chat-title" id="chat-title">New conversation</div>
            <div class="model-badge" id="model-badge">Ready</div>
        </div>

        <div class="messages-wrap" id="messages-wrap">
            <div class="empty-state" id="empty-state">
                <div class="empty-icon">⚡</div>
                <h2>Build any form instantly</h2>
                <p>Describe the form you need and get complete PHP + MySQL code ready to deploy.</p>
                <div class="suggestions">
                    <div class="suggestion-card" onclick="useSuggestion(this)">
                        <div class="s-label">User Registration</div>
                        <div class="s-sub">Name, email, password with validation</div>
                    </div>
                    <div class="suggestion-card" onclick="useSuggestion(this)">
                        <div class="s-label">Contact Form</div>
                        <div class="s-sub">Subject, message &amp; email delivery</div>
                    </div>
                    <div class="suggestion-card" onclick="useSuggestion(this)">
                        <div class="s-label">Job Application</div>
                        <div class="s-sub">Resume upload, skills, cover letter</div>
                    </div>
                    <div class="suggestion-card" onclick="useSuggestion(this)">
                        <div class="s-label">Order / Checkout</div>
                        <div class="s-sub">Product, quantity, address &amp; payment</div>
                    </div>
                </div>
            </div>
            <div class="messages-inner" id="messages-inner"></div>
        </div>

        <div class="input-area">
            <div class="input-box">
                <textarea id="prompt-input" rows="1"
                    placeholder="Describe the form you want to generate…"
                    onkeydown="handleKey(event)"
                    oninput="autoResize(this)"></textarea>
                <button class="send-btn" id="send-btn" onclick="sendMessage()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
                </button>
            </div>
            <!-- <div class="input-hint">Enter to send &nbsp;·&nbsp; Shift+Enter for new line</div> -->
        </div>
    </main>
</div>

<script>
    let sessions  = JSON.parse(localStorage.getItem('ff_sessions') || '[]');
    let currentId = null;

    renderHistoryList();

    function saveSession(id, title, messages) {
        const idx = sessions.findIndex(s => s.id === id);
        if (idx >= 0) sessions[idx] = { id, title, messages };
        else sessions.unshift({ id, title, messages });
        localStorage.setItem('ff_sessions', JSON.stringify(sessions));
        renderHistoryList();
    }

    function renderHistoryList() {
        const list = document.getElementById('history-list');
        list.innerHTML = '';
        if (!sessions.length) {
            list.innerHTML = '<div style="padding:10px 20px;font-size:12.5px;color:var(--text-muted)">No conversations yet</div>';
            return;
        }
        sessions.forEach(s => {
            const el = document.createElement('div');
            el.className = 'history-item' + (s.id === currentId ? ' active' : '');
            el.innerHTML = '<span style="opacity:.45;font-size:13px"></span><span class="item-label">' + escHtml(s.title) + '</span>';
            el.onclick = () => loadSession(s.id);
            list.appendChild(el);
        });
    }

    function loadSession(id) {
        const session = sessions.find(s => s.id === id);
        if (!session) return;
        currentId = id;
        document.getElementById('chat-title').textContent   = session.title;
        document.getElementById('messages-inner').innerHTML = '';
        document.getElementById('empty-state').style.display = 'none';
        session.messages.forEach(m => renderMessage(m.role, m.content, m.resultHtml || '', false));
        renderHistoryList();
        scrollBottom();
    }

    function newChat() {
        currentId = null;
        document.getElementById('messages-inner').innerHTML  = '';
        document.getElementById('empty-state').style.display = 'block';
        document.getElementById('chat-title').textContent    = 'New conversation';
        setModelBadge('Ready');
        renderHistoryList();
    }

    function addMessageToSession(msg) {
        const s = sessions.find(s => s.id === currentId);
        if (s) { s.messages.push(msg); localStorage.setItem('ff_sessions', JSON.stringify(sessions)); }
    }

    function setModelBadge(label) {
        document.getElementById('model-badge').textContent = label;
    }

    function handleKey(e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } }
    function autoResize(el) { el.style.height = 'auto'; el.style.height = Math.min(el.scrollHeight, 160) + 'px'; }
    function useSuggestion(el) {
        document.getElementById('prompt-input').value =
            el.querySelector('.s-label').textContent + ' — ' + el.querySelector('.s-sub').textContent;
        sendMessage();
    }

    async function sendMessage() {
        const input  = document.getElementById('prompt-input');
        const prompt = input.value.trim();
        if (!prompt) return;

        document.getElementById('empty-state').style.display = 'none';

        if (!currentId) {
            currentId = 'session_' + Date.now();
            const title = prompt.length > 40 ? prompt.slice(0, 40) + '…' : prompt;
            document.getElementById('chat-title').textContent = title;
            saveSession(currentId, title, []);
        }

        const userMsg = { role: 'user', content: prompt };
        addMessageToSession(userMsg);
        renderMessage('user', prompt, '', false);
        input.value = ''; input.style.height = 'auto';

        setBusy(true);
        const typingEl = showTyping();

        try {
            const fd = new FormData();
            fd.append('prompt', prompt);
            const res  = await fetch('generate.php', { method: 'POST', body: fd });
            const data = await res.json();
            typingEl.remove();

            if (data.error) {
               const errHtml = '<div class="error-msg">⚠️ <pre>' + 
                escHtml(JSON.stringify(data, null, 2)) + 
                '</pre></div>';
                const botMsg  = { role: 'bot', content: 'Something went wrong.', resultHtml: errHtml };
                addMessageToSession(botMsg);
                renderMessage('bot', botMsg.content, errHtml, true);
            } else {
                if (data.model) setModelBadge(data.model);
                const resultHtml = buildResultCard(data);
                const botMsg = { role: 'bot', content: 'Your form files are ready!', resultHtml };
                addMessageToSession(botMsg);
                renderMessage('bot', botMsg.content, resultHtml, true);
            }
        } catch (err) {
            typingEl.remove();
            renderMessage('bot', 'Network error.', '<div class="error-msg">' + escHtml(err.message) + '</div>', true);
        }

        setBusy(false);
        scrollBottom();
    }

    function buildResultCard(data) {
        const folderHtml = data.folder
            ? '<div class="folder-info"> Saved to <code>generated/' + escHtml(data.folder) + '/</code></div>'
            : '';

        const setupHtml = data.setup_instructions
            ? '<div class="setup-card">' +
              '<button class="setup-toggle" onclick="toggleSetup(this)"> Setup Instructions <span class="setup-chevron">▾</span></button>' +
              '<div class="setup-body">' + escHtml(data.setup_instructions) + '</div>' +
              '</div>'
            : '';

        return '<div class="result-card">' +
            '<div class="result-card-header">Generated Files</div>' +
            '<div class="result-card-body">' +
            '<a href="' + escHtml(data.php_file) + '" download class="download-btn primary"> Download form.php</a>' +
            '<a href="' + escHtml(data.sql_file) + '" download class="download-btn primary"> Download database.sql</a>' +
            
            '</div></div>' +
            folderHtml + setupHtml;
    }

    function toggleSetup(btn) {
        btn.classList.toggle('open');
        btn.nextElementSibling.classList.toggle('visible');
    }

    function renderMessage(role, content, resultHtml, animate) {
        const wrap = document.getElementById('messages-inner');
        const row  = document.createElement('div');
        row.className = 'msg-row ' + (role === 'user' ? 'user' : 'bot');
        if (!animate) row.style.animation = 'none';
        const avatar = role === 'user'
            ? '<div class="avatar user-av">U</div>'
            : '<div class="avatar bot">F</div>';
        row.innerHTML = avatar + '<div class="bubble">' + escHtml(content) + (resultHtml || '') + '</div>';
        wrap.appendChild(row);
        scrollBottom();
    }

    function showTyping() {
        const wrap = document.getElementById('messages-inner');
        const row  = document.createElement('div');
        row.className = 'msg-row bot';
        row.innerHTML = '<div class="avatar bot">F</div><div class="typing-dots"><span></span><span></span><span></span></div>';
        wrap.appendChild(row);
        scrollBottom();
        return row;
    }

    function setBusy(busy) {
        document.getElementById('send-btn').disabled     = busy;
        document.getElementById('prompt-input').disabled = busy;
    }

    function scrollBottom() { const w = document.getElementById('messages-wrap'); w.scrollTop = w.scrollHeight; }

    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
</script>
</body>
</html>