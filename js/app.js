const balanceElement = document.getElementById('balance');
const toggleButton = document.getElementById('toggleVisibility');
let originalBalance = balanceElement.textContent;

// Create asterisks of similar length to the original balance
const hiddenBalance = originalBalance.replace(/[^\s]/g, '*');

// Read saved state from localStorage, default to visible if not set
let isVisible = localStorage.getItem('balanceVisible') === 'false' ? false : true;

// Function to update the display based on `isVisible`
function updateBalanceDisplay() {
    if (isVisible) {
        balanceElement.textContent = originalBalance;
        toggleButton.classList.remove('ri-eye-close-line');
        toggleButton.classList.add('ri-eye-line');
    } else {
        balanceElement.textContent = hiddenBalance;
        toggleButton.classList.remove('ri-eye-line');
        toggleButton.classList.add('ri-eye-close-line');
    }
}

// Initial load state
updateBalanceDisplay();

toggleButton.addEventListener('click', function () {
    isVisible = !isVisible;
    localStorage.setItem('balanceVisible', isVisible); // Save state
    updateBalanceDisplay();
});