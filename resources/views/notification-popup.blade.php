<div id="notif-popup-root"></div>

<style>
    #notif-overlay { transition: opacity 0.2s ease; }
    #notif-card { transition: all 0.3s ease; }
</style>

<script>
(function() {
    const shownIds = new Set();
    let currentId = null;
    let dismissTimer = null;

    // Browser Notification: request permission
    if ('Notification' in window && Notification.permission === 'default') {
        document.addEventListener('click', function requestPerm() {
            Notification.requestPermission();
            document.removeEventListener('click', requestPerm);
        }, { once: true });
    }

    function showBrowserNotification(n) {
        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                new Notification(n.title, {
                    body: n.body,
                    icon: '/favicon.ico',
                    badge: '/favicon.ico',
                    tag: n.id,
                    requireInteraction: false,
                });
            } catch(e) {}
        }
    }

    function getColor(type) {
        const colors = {
            success: {
                headerBg: '#16a34a',
                iconBg: '#dcfce7',
                btnBg: '#16a34a',
                btnHover: '#15803d',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:32px;height:32px;color:#16a34a"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>'
            },
            warning: {
                headerBg: '#d97706',
                iconBg: '#fef3c7',
                btnBg: '#d97706',
                btnHover: '#b45309',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:32px;height:32px;color:#d97706"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>'
            },
            danger: {
                headerBg: '#dc2626',
                iconBg: '#fee2e2',
                btnBg: '#dc2626',
                btnHover: '#b91c1c',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:32px;height:32px;color:#dc2626"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>'
            },
            primary: {
                headerBg: '#d97706',
                iconBg: '#fef3c7',
                btnBg: '#d97706',
                btnHover: '#b45309',
                svg: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:32px;height:32px;color:#d97706"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75v-.7V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg>'
            }
        };
        return colors[type] || colors.primary;
    }

    function showPopup(n) {
        if (document.getElementById('notif-card')) return;
        currentId = n.id;
        const c = getColor(n.iconColor);

        showBrowserNotification(n);

        const html = `
            <div id="notif-card" style="position:fixed;top:1rem;right:1rem;z-index:9999;width:22rem;overflow:hidden;border-radius:0.75rem;background:white;box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);border-left:4px solid ${c.headerBg};transform:translateX(120%);opacity:0;transition:all 0.3s ease;">
                <div style="padding:1rem;display:flex;align-items:flex-start;gap:0.75rem;">
                    <div style="flex-shrink:0;background:${c.iconBg};display:flex;align-items:center;justify-content:center;width:2.5rem;height:2.5rem;border-radius:9999px;">
                        ${c.svg}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:0.875rem;font-weight:600;color:#1c1917;margin:0;line-height:1.3;">${n.title}</p>
                        <p style="font-size:0.8125rem;color:#57534e;margin:0.25rem 0 0;line-height:1.4;">${n.body}</p>
                        <p style="font-size:0.6875rem;color:#a8a29e;margin:0.25rem 0 0;">${n.created_at}</p>
                    </div>
                    <button onclick="window.__dismissNotif()" style="flex-shrink:0;background:none;border:none;cursor:pointer;padding:0.25rem;color:#a8a29e;font-size:1.125rem;line-height:1;" onmouseover="this.style.color='#57534e'" onmouseout="this.style.color='#a8a29e'">&times;</button>
                </div>
            </div>
        `;

        const root = document.getElementById('notif-popup-root');
        root.insertAdjacentHTML('beforeend', html);

        const card = document.getElementById('notif-card');
        requestAnimationFrame(() => {
            card.style.transform = 'translateX(0)';
            card.style.opacity = '1';
        });

        dismissTimer = setTimeout(() => window.__dismissNotif(), 5000);
    }

    window.__dismissNotif = function() {
        if (dismissTimer) { clearTimeout(dismissTimer); dismissTimer = null; }

        if (currentId) {
            fetch('/api/notifications/' + currentId + '/read', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-XSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            shownIds.add(currentId);
            currentId = null;
        }

        const card = document.getElementById('notif-card');
        if (card) {
            card.style.transform = 'translateX(120%)';
            card.style.opacity = '0';
            setTimeout(() => card.remove(), 300);
        }
    };

    function poll() {
        fetch('/api/notifications/unread', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            const notifs = data.notifications || [];
            const fresh = notifs.find(n => !shownIds.has(n.id));
            if (fresh && !document.getElementById('notif-card')) {
                showPopup(fresh);
            }
        })
        .catch(() => {});
    }

    setInterval(poll, 5000);
    setTimeout(poll, 2000);
})();
</script>
