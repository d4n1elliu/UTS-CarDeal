document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector("form");
    const fields = ['first_name', 'last_name', 'email', 'phone'];
    const qtyInput = document.getElementById("quantity");
    const searchInput = document.getElementById("searchInput");
    const suggestionsBox = document.getElementById("suggestions");
    const startInput = document.getElementById("start_date");
    const endInput = document.getElementById("end_date");
    const daysInput = document.getElementById("rentalDays");
    let timeout = null;

    // 1. Data validation
    if (form) {
        form.addEventListener("submit", function (event) {
            if (startInput && endInput) {
                const startDate = new Date(startInput.value);
                const endDate = new Date(endInput.value);

                if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
                    alert("Please select valid dates.");
                    event.preventDefault();
                    return;
                }

                const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                if (diffDays <= 0) {
                    alert("End date must be after start date.");
                    event.preventDefault();
                    return;
                }

                if (daysInput) {
                    daysInput.value = diffDays;
                }
            }
               

            // Quantity validation
            if (qtyInput && qtyInput.dataset.max) {
                const maxAvailable = parseInt(qtyInput.dataset.max);
                const qty = parseInt(qtyInput.value);

                if (qty > maxAvailable) {
                    alert(`Only ${maxAvailable} vehicle(s) are available. Please adjust your quantity.`);
                    event.preventDefault();
                }
            }
        });
    }

    // 2. Input fields used to store in cookies
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            // Load cookie value if it exists
            const cookieValue = getCookie(field);
            if (cookieValue) input.value = decodeURIComponent(cookieValue);

            // Save to cookie on input change
            input.addEventListener('input', () => {
                document.cookie = `${field}=${encodeURIComponent(input.value)}; path=/; max-age=3600`;
            });
        }
    });

    // 3. Real-time search suggestions
    if (searchInput && suggestionsBox) {
        let timeout = null;

        searchInput.addEventListener("input", () => {
            clearTimeout(timeout);
            const query = searchInput.value.trim();

            if (query.length === 0) {
                suggestionsBox.innerHTML = "";
                suggestionsBox.style.display = "none";
                return;
            }

            timeout = setTimeout(() => {
                fetch(`./search_suggestion.php?term=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsBox.innerHTML = "";

                        if (data.length === 0) {
                            suggestionsBox.style.display = "none";
                            return;
                        }

                        suggestionsBox.style.display = "block";

                        data.forEach(item => {
                            const div = document.createElement("div");
                            div.className = "suggestion-item px-3 py-2";
                            div.textContent = item;
                            div.style.cursor = "pointer";
                            div.addEventListener("click", () => {
                                searchInput.value = item;
                                suggestionsBox.innerHTML = "";
                                searchInput.form.submit();
                            });
                            suggestionsBox.appendChild(div);
                        });
                    });
            }, 200);
        });

        document.addEventListener("click", (e) => {
            if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                suggestionsBox.innerHTML = "";
                suggestionsBox.style.display = "none";
            }
        });
    }
});

// Helper function for cookie 
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    return parts.length > 1 ? parts.pop().split(';')[0] : '';
}