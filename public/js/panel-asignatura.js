document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabTriggers = document.querySelectorAll('.tab-trigger');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            // Remove active class from all triggers and contents
            tabTriggers.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked trigger and corresponding content
            trigger.classList.add('active');
            const tabId = trigger.getAttribute('data-tab');
            document.getElementById(`${tabId}-content`).classList.add('active');
        });
    });
    
    // Pagination
    let currentPage = 1;
    const totalPages = 3;
    
    const updatePaginationButtons = () => {
        document.querySelectorAll('.pagination-btn[data-page]').forEach(btn => {
            const page = parseInt(btn.getAttribute('data-page'));
            if (page === currentPage) {
                btn.classList.add('pagination-btn-active');
            } else {
                btn.classList.remove('pagination-btn-active');
            }
        });
        
        // Enable/disable prev/next buttons
        document.getElementById('prev-btn').disabled = currentPage === 1;
        document.getElementById('next-btn').disabled = currentPage === totalPages;
    };
    
    // Page number buttons
    document.querySelectorAll('.pagination-btn[data-page]').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPage = parseInt(btn.getAttribute('data-page'));
            updatePaginationButtons();
        });
    });
    
    // Previous button
    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePaginationButtons();
        }
    });
    
    // Next button
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updatePaginationButtons();
        }
    });
    
    // Initialize pagination state
    updatePaginationButtons();
});
