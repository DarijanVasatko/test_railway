document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length > 2) {
                fetch(`autocomplete.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                  
                        console.log(data); 
                    });
            }
        });
    }

    searchForm.addEventListener('submit', function(e) {
        if (searchInput.value.trim() === '') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});