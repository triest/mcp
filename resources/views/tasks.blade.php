<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Task Tracker</title>
<style>
  :root { color-scheme: light; }
  * { box-sizing: border-box; }
  body {
    margin: 0; font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    background: #f4f5f7; color: #1f2328;
  }
  header {
    background: #1f2328; color: #fff; padding: 16px 24px;
    display: flex; justify-content: space-between; align-items: center;
  }
  header h1 { font-size: 18px; margin: 0; font-weight: 600; }
  #userBox { font-size: 13px; opacity: .85; }
  #userBox button { margin-left: 10px; }
  main { max-width: 900px; margin: 24px auto; padding: 0 16px; }
  .card {
    background: #fff; border: 1px solid #e2e4e8; border-radius: 8px;
    padding: 16px; margin-bottom: 16px;
  }
  .card h2 { font-size: 14px; margin: 0 0 12px; color: #57606a; }
  input, select, textarea, button {
    font: inherit; padding: 8px 10px; border: 1px solid #d0d7de; border-radius: 6px;
  }
  input, textarea { width: 100%; margin-bottom: 8px; }
  button {
    background: #1f6feb; color: #fff; border: none; cursor: pointer; padding: 8px 14px;
  }
  button.secondary { background: #eaeef2; color: #1f2328; }
  button.danger { background: #d1242f; }
  form.inline { display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-start; }
  form.inline > * { flex: 1 1 160px; }
  form.inline button { flex: 0 0 auto; }
  ul#taskList { list-style: none; margin: 0; padding: 0; }
  li.task {
    border: 1px solid #e2e4e8; border-radius: 6px; padding: 10px 12px; margin-bottom: 8px;
    display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;
  }
  li.task .meta { flex: 1 1 auto; min-width: 200px; }
  li.task .title { font-weight: 600; }
  li.task .desc { font-size: 13px; color: #57606a; margin-top: 2px; }
  .badge {
    font-size: 11px; padding: 2px 8px; border-radius: 999px; text-transform: uppercase;
    font-weight: 600; letter-spacing: .3px;
  }
  .badge.todo { background: #ddf4ff; color: #0969da; }
  .badge.in_progress { background: #fff8c5; color: #9a6700; }
  .badge.done { background: #dafbe1; color: #1a7f37; }
  .badge.cancelled { background: #ffebe9; color: #cf222e; }
  .actions { display: flex; gap: 6px; }
  .actions select, .actions button { padding: 6px 8px; font-size: 12px; }
  #authScreen { max-width: 360px; margin: 60px auto; }
  #error { color: #d1242f; font-size: 13px; margin-top: 6px; min-height: 16px; }
  #mcpBox code { background: #f6f8fa; padding: 2px 6px; border-radius: 4px; font-size: 12px; word-break: break-all; }
</style>
</head>
<body>

<header>
  <h1>Task Tracker</h1>
  <div id="userBox"></div>
</header>

<main>
  <div id="authScreen" class="card" hidden>
    <h2>Вход / регистрация</h2>
    <form id="loginForm" class="inline" style="flex-direction:column;">
      <input type="email" id="email" placeholder="email" required>
      <input type="text" id="name" placeholder="имя (только для регистрации)">
      <input type="password" id="password" placeholder="пароль (мин. 8 символов)" required>
      <div style="display:flex; gap:8px;">
        <button type="submit" data-action="login">Войти</button>
        <button type="submit" data-action="register" class="secondary">Зарегистрироваться</button>
      </div>
    </form>
    <div id="error"></div>
  </div>

  <div id="app" hidden>
    <div class="card">
      <h2>Новая задача</h2>
      <form id="createForm" class="inline">
        <input type="text" id="newTitle" placeholder="Название задачи" required>
        <input type="text" id="newDesc" placeholder="Описание (необязательно)">
        <button type="submit">Добавить</button>
      </form>
    </div>

    <div class="card">
      <h2 style="display:flex; justify-content:space-between; align-items:center;">
        <span>Задачи</span>
        <select id="statusFilter">
          <option value="">Все статусы</option>
          <option value="todo">todo</option>
          <option value="in_progress">in_progress</option>
          <option value="done">done</option>
          <option value="cancelled">cancelled</option>
        </select>
      </h2>
      <ul id="taskList"></ul>
      <div id="empty" style="color:#57606a; font-size:13px;" hidden>Пока нет задач.</div>
    </div>

    <div class="card" id="mcpBox">
      <h2>Подключение по MCP</h2>
      <p style="font-size:13px; color:#57606a; margin:0 0 8px;">
        Токен для MCP-коннектора (создаётся один раз на аккаунт):
      </p>
      <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <button id="issueTokenBtn" class="secondary">Выпустить MCP-токен</button>
        <code id="mcpTokenOut"></code>
      </div>
    </div>
  </div>
</main>

<script>
const API = '/api';
let token = localStorage.getItem('access_token');

const $ = (sel) => document.querySelector(sel);
const authScreen = $('#authScreen');
const app = $('#app');
const userBox = $('#userBox');
const errorBox = $('#error');

function setError(msg) { errorBox.textContent = msg || ''; }

async function api(path, options = {}) {
  const res = await fetch(API + path, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token ? { Authorization: 'Bearer ' + token } : {}),
      ...(options.headers || {}),
    },
  });
  if (res.status === 401) {
    logout();
    throw new Error('Сессия истекла, войдите снова');
  }
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.message || 'Ошибка запроса');
  return data;
}

function logout() {
  token = null;
  localStorage.removeItem('access_token');
  render();
}

function render() {
  if (token) {
    authScreen.hidden = true;
    app.hidden = false;
    userBox.innerHTML = '<button class="secondary" id="logoutBtn">Выйти</button>';
    $('#logoutBtn').onclick = logout;
    loadTasks();
  } else {
    authScreen.hidden = false;
    app.hidden = true;
    userBox.innerHTML = '';
  }
}

$('#loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const action = e.submitter?.dataset.action || 'login';
  const email = $('#email').value.trim();
  const password = $('#password').value;
  const name = $('#name').value.trim();
  setError('');
  try {
    const payload = action === 'register'
      ? { email, name: name || email.split('@')[0], password }
      : { email, password };
    const data = await api('/auth/' + action, { method: 'POST', body: JSON.stringify(payload) });
    token = data.access_token;
    localStorage.setItem('access_token', token);
    render();
  } catch (err) {
    setError(err.message);
  }
});

$('#createForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const title = $('#newTitle').value.trim();
  const description = $('#newDesc').value.trim();
  if (!title) return;
  await api('/tasks', { method: 'POST', body: JSON.stringify({ title, description: description || null }) });
  $('#newTitle').value = '';
  $('#newDesc').value = '';
  loadTasks();
});

$('#statusFilter').addEventListener('change', loadTasks);

$('#issueTokenBtn').addEventListener('click', async () => {
  try {
    const data = await api('/mcp-tokens', { method: 'POST', body: JSON.stringify({ name: 'web-ui' }) });
    const url = location.origin + '/api/mcp?token=' + data.token;
    $('#mcpTokenOut').textContent = url;
  } catch (err) {
    setError(err.message);
  }
});

async function loadTasks() {
  const status = $('#statusFilter').value;
  const data = await api('/tasks' + (status ? ('?status=' + status) : ''));
  const list = $('#taskList');
  list.innerHTML = '';
  $('#empty').hidden = data.length > 0;
  for (const task of data) {
    const li = document.createElement('li');
    li.className = 'task';
    li.innerHTML = `
      <div class="meta">
        <div class="title">${escapeHtml(task.title)} <span class="badge ${task.status}">${task.status}</span></div>
        ${task.description ? `<div class="desc">${escapeHtml(task.description)}</div>` : ''}
      </div>
      <div class="actions">
        <select data-id="${task.id}" class="statusSelect">
          ${['todo', 'in_progress', 'done', 'cancelled'].map(s => `<option value="${s}" ${s === task.status ? 'selected' : ''}>${s}</option>`).join('')}
        </select>
        <button class="danger" data-id="${task.id}" data-action="delete">Удалить</button>
      </div>
    `;
    list.appendChild(li);
  }

  list.querySelectorAll('.statusSelect').forEach(sel => {
    sel.addEventListener('change', async () => {
      await api('/tasks/' + sel.dataset.id, { method: 'PATCH', body: JSON.stringify({ status: sel.value }) });
      loadTasks();
    });
  });
  list.querySelectorAll('[data-action="delete"]').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!confirm('Удалить задачу?')) return;
      await api('/tasks/' + btn.dataset.id, { method: 'DELETE' });
      loadTasks();
    });
  });
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

render();
</script>
</body>
</html>
