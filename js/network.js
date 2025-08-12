const selectedCoin = document.getElementById('selectedCoin');
const coinDropdown = document.getElementById('coinDropdown');
const searchCoin = document.getElementById('searchCoin');
const coinItems = document.querySelectorAll('.coin-item');
const networkSection = document.getElementById('networkSection');
const networkContainer = document.getElementById('networkContainer');
const networkLoader = document.getElementById('networkLoader');
const assetCoin = document.getElementById('assetCoin');
const assetCoin2 = document.getElementById('assetCoin2');

// Attention panel fields
const minDepositEl = document.getElementById('minDeposit');
const depositConfEl = document.getElementById('depositConf');
const withdrawalConfEl = document.getElementById('withdrawalConf');

const networks = {
    USDT: ["BSC (BEP20)", "TRON (TRC20)", "Ethereum (ERC20)", "Solana"],
    BTC: ["Bitcoin Mainnet", "Lightning Network"],
    ETH: ["Ethereum Mainnet", "Polygon", "Arbitrum"]
};

const networkInfo = {
    "BSC (BEP20)": { min: "0.5 USDT", depositConf: "12 Confirmation(s)", withdrawalConf: "30 Confirmation(s)" },
    "TRON (TRC20)": { min: "1 USDT", depositConf: "10 Confirmation(s)", withdrawalConf: "25 Confirmation(s)" },
    "Ethereum (ERC20)": { min: "5 USDT", depositConf: "15 Confirmation(s)", withdrawalConf: "40 Confirmation(s)" },
    "Solana": { min: "0.2 USDT", depositConf: "8 Confirmation(s)", withdrawalConf: "20 Confirmation(s)" },
    "Bitcoin Mainnet": { min: "0.0005 BTC", depositConf: "2 Confirmation(s)", withdrawalConf: "6 Confirmation(s)" },
    "Lightning Network": { min: "0.0001 BTC", depositConf: "Instant", withdrawalConf: "Instant" },
    "Ethereum Mainnet": { min: "0.05 ETH", depositConf: "15 Confirmation(s)", withdrawalConf: "40 Confirmation(s)" },
    "Polygon": { min: "1 MATIC", depositConf: "12 Confirmation(s)", withdrawalConf: "25 Confirmation(s)" },
    "Arbitrum": { min: "0.05 ETH", depositConf: "15 Confirmation(s)", withdrawalConf: "35 Confirmation(s)" }
};

// Show loader while fetching networks
function loadNetworks(coin) {
    networkContainer.innerHTML = '';
    networkLoader.style.display = 'block';

    setTimeout(() => {
        networkLoader.style.display = 'none';
        if (networks[coin]) {
            networks[coin].forEach(net => {
                const col = document.createElement('div');
                col.classList.add('col-6');
                const btn = document.createElement('div');
                btn.classList.add('network-btn');
                btn.textContent = net;
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.network-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const info = networkInfo[net];
                    if (info) {
                        minDepositEl.textContent = info.min;
                        depositConfEl.textContent = info.depositConf;
                        withdrawalConfEl.textContent = info.withdrawalConf;
                    }
                });
                col.appendChild(btn);
                networkContainer.appendChild(col);
            });
        }
    }, 800); // simulate loader delay
}

selectedCoin.addEventListener('click', () => {
    coinDropdown.style.display = coinDropdown.style.display === 'block' ? 'none' : 'block';
});

coinItems.forEach(item => {
    item.addEventListener('click', () => {
        const coin = item.dataset.coin;
        selectedCoin.querySelector('span').textContent = coin;
        assetCoin.textContent = coin;
        assetCoin2.textContent = coin;
        coinDropdown.style.display = 'none';
        loadNetworks(coin);
    });
});

// Handle History and Popular Coins badges
document.querySelectorAll('.coin-badge').forEach(badge => {
    badge.addEventListener('click', () => {
        const coin = badge.textContent.trim();
        selectedCoin.querySelector('span').textContent = coin;
        assetCoin.textContent = coin;
        assetCoin2.textContent = coin;
        coinDropdown.style.display = 'none';
        loadNetworks(coin);
    });
});


searchCoin.addEventListener('input', () => {
    const filter = searchCoin.value.toLowerCase();
    coinItems.forEach(item => {
        item.style.display = item.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
});

// On page load, show USDT as default
document.addEventListener('DOMContentLoaded', () => {
    selectedCoin.querySelector('span').textContent = 'USDT';
    assetCoin.textContent = 'USDT';
    assetCoin2.textContent = 'USDT';
    loadNetworks('USDT');
});