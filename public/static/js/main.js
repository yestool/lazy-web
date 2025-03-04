
// 页面加载时检查侧边栏状态
document.addEventListener('DOMContentLoaded', function() {
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed) {
        document.querySelector('.sidebar').classList.add('collapsed');
        document.querySelector('.main-content').classList.add('expanded');
    }
});

// 侧边栏切换功能
document.getElementById('sidebarToggle').addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
    // 保存状态到localStorage
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

    if (window.innerWidth < 768) {
        sidebar.classList.toggle('show');
        document.querySelector('.sidebar-backdrop').classList.toggle('show');
    }
});

// 点击遮罩层关闭侧边栏
document.querySelector('.sidebar-backdrop').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('show', 'collapsed');
    document.querySelector('.main-content').classList.remove('expanded');
    document.querySelector('.sidebar-backdrop').classList.remove('show');
    // 更新localStorage中的状态
    localStorage.setItem('sidebarCollapsed', false);
});


document.addEventListener('DOMContentLoaded', () => {
    Alpine.data('AdminLayoutData', () => ({
        showSidebar: false,
    }));
});