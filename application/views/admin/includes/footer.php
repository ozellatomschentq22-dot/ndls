    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Common Admin Scripts -->
    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    }
                }, 5000);
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
        
        // Common confirmation function
        function confirmAction(message, url) {
            if (confirm(message || 'Are you sure you want to perform this action?')) {
                window.location.href = url;
            }
        }
        
        // Common search function
        function performSearch(searchTerm, containerSelector, itemSelector, searchFields) {
            const container = document.querySelector(containerSelector);
            const items = container.querySelectorAll(itemSelector);
            const noResults = document.getElementById('noResults');
            
            let visibleCount = 0;
            
            items.forEach(function(item) {
                let text = '';
                searchFields.forEach(function(field) {
                    text += ' ' + (item.getAttribute('data-' + field) || '');
                });
                
                if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (noResults) {
                if (visibleCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        }
        
        // Common filter function
        function performFilter(filterValue, containerSelector, itemSelector, filterField) {
            const container = document.querySelector(containerSelector);
            const items = container.querySelectorAll(itemSelector);
            const noResults = document.getElementById('noResults');
            
            let visibleCount = 0;
            
            items.forEach(function(item) {
                const itemValue = item.getAttribute('data-' + filterField);
                
                if (filterValue === 'all' || itemValue === filterValue) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (noResults) {
                if (visibleCount === 0) {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        }
        
        // Common sort function
        function performSort(sortBy, containerSelector, itemSelector, sortField, sortType = 'text') {
            const container = document.querySelector(containerSelector);
            const items = Array.from(container.querySelectorAll(itemSelector));
            
            items.sort(function(a, b) {
                let aValue = a.getAttribute('data-' + sortField);
                let bValue = b.getAttribute('data-' + sortField);
                
                if (sortType === 'number') {
                    aValue = parseFloat(aValue) || 0;
                    bValue = parseFloat(bValue) || 0;
                } else if (sortType === 'date') {
                    aValue = new Date(aValue);
                    bValue = new Date(bValue);
                }
                
                if (sortBy === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            items.forEach(function(item) {
                container.appendChild(item);
            });
        }
    </script>
</body>
</html> 