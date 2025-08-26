const balanceElement = document.getElementById('balance');
const toggleButton = document.getElementById('toggleVisibility');
let originalBalance = balanceElement ? balanceElement.textContent : '';
const priceStatus = document.getElementById('priceStatus');

// Create asterisks of similar length to the original balance
const hiddenBalance = originalBalance ? originalBalance.replace(/[^\s]/g, '*') : '';

// Read saved state from localStorage, default to visible if not set
let isVisible = localStorage.getItem('balanceVisible') === 'false' ? false : true;

// Function to update the display based on `isVisible`
function updateBalanceDisplay() {
    if (!balanceElement || !toggleButton) return;
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

if (toggleButton) {
    toggleButton.addEventListener('click', function () {
        isVisible = !isVisible;
        localStorage.setItem('balanceVisible', isVisible); // Save state
        updateBalanceDisplay();
    });
}

async function fetchJson(url) {
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Request failed');
    return res.json();
}

function formatNumber(n, decimals = 2) {
    return Number(n).toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

function updateTable(balances, prices) {
    let totalUsd = 0;
    balances.forEach(b => {
        const amtEl = document.querySelector(`.amt[data-asset="${b.asset}"]`);
        const valEl = document.querySelector(`.val[data-asset="${b.asset}"]`);
        const frzEl = document.querySelector(`.frozen[data-asset="${b.asset}"]`);
        const price = prices[b.asset] ?? 0;
        const available = parseFloat(b.available) || 0;
        const frozen = parseFloat(b.frozen) || 0;
        const usd = available * (price || 0);
        totalUsd += usd;
        if (amtEl) amtEl.textContent = formatNumber(available, 6);
        if (frzEl) frzEl.textContent = formatNumber(frozen, 6);
        if (valEl) valEl.textContent = `$${formatNumber(usd, 2)}`;
    });
    if (balanceElement) {
        balanceElement.textContent = `${formatNumber(totalUsd, 3)} ≈ $${formatNumber(totalUsd, 2)}`;
        originalBalance = balanceElement.textContent;
        updateBalanceDisplay();
    }
}

async function hydrateBalances() {
    try {
        if (priceStatus) priceStatus.textContent = 'Loading balances…';
        const [assets, balances] = await Promise.all([
            fetchJson('/api/assets'),
            fetchJson('/api/balances'),
        ]);
        const prices = {};
        (assets.data || []).forEach(a => { prices[a.symbol] = a.price_usd; });
        updateTable(balances.data || [], prices);
        if (priceStatus) priceStatus.textContent = '';
    } catch (e) {
        if (priceStatus) priceStatus.textContent = 'Failed to load balances/prices';
    }
}

document.addEventListener('DOMContentLoaded', hydrateBalances);
