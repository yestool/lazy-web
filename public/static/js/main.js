// 侧边栏切换功能
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('show');
    document.querySelector('.sidebar-backdrop').classList.toggle('show');
});

// 点击遮罩层关闭侧边栏
document.querySelector('.sidebar-backdrop').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('show');
    document.querySelector('.sidebar-backdrop').classList.remove('show');
});

document.addEventListener('DOMContentLoaded', () => {
    Alpine.data('AdminLayoutData', () => ({
        showSidebar: false,
    }));
});