// Network configurations for each coin
const coinNetworks = {
    usdt: [
        {
            name: 'USDT',
            description: 'Native USDT network',
            icon: 'fas fa-coins',
            color: '#0033ad',
            id: 'usdt-native'
        }
    ],
    usdc: [
        {
            name: 'Ethereum (ERC20)',
            description: 'Standard Ethereum network',
            icon: 'fab fa-ethereum',
            color: '#627eea',
            id: 'eth-erc20'
        },
        {
            name: 'Solana',
            description: 'Fast & low fees',
            icon: 'fas fa-sun',
            color: '#9945ff',
            id: 'solana'
        },
        {
            name: 'Polygon',
            description: 'Layer 2 solution',
            icon: 'fas fa-layer-group',
            color: '#8247e5',
            id: 'polygon'
        },
        {
            name: 'Avalanche',
            description: 'High throughput',
            icon: 'fas fa-mountain',
            color: '#e84142',
            id: 'avalanche'
        }
    ],
    bitcoin: [
        {
            name: 'Bitcoin',
            description: 'Native Bitcoin network',
            icon: 'fab fa-bitcoin',
            color: '#f7931a',
            id: 'bitcoin-native'
        },
        {
            name: 'Lightning Network',
            description: 'Instant payments',
            icon: 'fas fa-bolt',
            color: '#ffd700',
            id: 'bitcoin-lightning'
        }
    ],
    ethereum: [
        {
            name: 'Ethereum',
            description: 'Native Ethereum network',
            icon: 'fab fa-ethereum',
            color: '#627eea',
            id: 'ethereum-native'
        },
        {
            name: 'Arbitrum',
            description: 'Layer 2 scaling',
            icon: 'fas fa-arrow-up',
            color: '#28a0f0',
            id: 'arbitrum'
        },
        {
            name: 'Optimism',
            description: 'Optimistic rollups',
            icon: 'fas fa-rocket',
            color: '#ff0420',
            id: 'optimism'
        }
    ],
    solana: [
        {
            name: 'Solana',
            description: 'Native Solana network',
            icon: 'fas fa-sun',
            color: '#9945ff',
            id: 'solana-native'
        }
    ],
    cardano: [
        {
            name: 'Cardano',
            description: 'Native Cardano network',
            icon: 'fas fa-coins',
            color: '#0033ad',
            id: 'cardano-native'
        }
    ]
};

document.addEventListener('DOMContentLoaded', function () {
    const selectBtn = document.getElementById('coinSelectBtn');
    const dropdown = document.getElementById('coinDropdown');
    const dropdownArrow = document.getElementById('dropdownArrow');
    const selectedIcon = document.getElementById('selectedIcon');
    const selectedName = document.getElementById('selectedName');
    const selectedSymbol = document.getElementById('selectedSymbol');
    const coinOptions = document.querySelectorAll('.coin-option');
    const networkGrid = document.getElementById('networkGrid');

    let selectedNetwork = null;

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize with USDC networks
    updateNetworks('usdc');

    // Toggle dropdown
    selectBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = dropdown.classList.contains('show');

        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });

    // Handle coin selection
    coinOptions.forEach(option => {
        option.addEventListener('click', function () {
            const coinId = this.getAttribute('data-coin-id');
            const coinIconEl = this.querySelector('.coin-icon');
            const coinNameEl = this.querySelector('.fw-bold');
            const coinSymbolEl = this.querySelector('small');

            // Update selected display
            selectedIcon.innerHTML = coinIconEl.innerHTML;
            selectedIcon.style.background = coinIconEl.style.background;
            selectedName.textContent = coinNameEl.textContent;
            selectedSymbol.textContent = coinSymbolEl.textContent;

            // Update networks for selected coin
            updateNetworks(coinId);

            // Close dropdown
            closeDropdown();
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!selectBtn.contains(e.target) && !dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    // Keyboard navigation
    selectBtn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            selectBtn.click();
        } else if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    function openDropdown() {
        dropdown.classList.add('show');
        selectBtn.classList.add('active');
        dropdownArrow.classList.add('rotated');
        selectBtn.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
        dropdown.classList.remove('show');
        selectBtn.classList.remove('active');
        dropdownArrow.classList.remove('rotated');
        selectBtn.setAttribute('aria-expanded', 'false');
    }

    function updateNetworks(coinId) {
        const networks = coinNetworks[coinId] || [];
        selectedNetwork = null;

        networkGrid.innerHTML = '';

        networks.forEach(network => {
            const col = document.createElement('div');
            col.className = 'col-sm-6 col-lg-4';

            const networkCard = document.createElement('div');
            networkCard.className = 'network-card card border-2 text-center p-3';
            networkCard.setAttribute('data-network-id', network.id);

            networkCard.innerHTML = `
                        <div class="network-icon rounded-2 d-flex align-items-center justify-content-center text-white fs-5 mx-auto mb-2" 
                             style="width: 40px; height: 40px; background: ${network.color};">
                            <i class="${network.icon}"></i>
                        </div>
                        <div class="fw-bold mb-1">${network.name}</div>
                        <small class="text-muted">${network.description}</small>
                    `;

            networkCard.addEventListener('click', function () {
                // Remove selected class from all networks
                document.querySelectorAll('.network-card').forEach(card => {
                    card.classList.remove('selected');
                });

                // Add selected class to clicked network
                this.classList.add('selected');
                selectedNetwork = network.id;

                console.log('Selected network:', selectedNetwork);
            });

            col.appendChild(networkCard);
            networkGrid.appendChild(col);
        });

        // Auto-select first network if only one is available
        if (networks.length === 1) {
            setTimeout(() => {
                networkGrid.querySelector('.network-card').click();
            }, 100);
        }
    }

    // Initialize accessibility attributes
    selectBtn.setAttribute('aria-expanded', 'false');
    selectBtn.setAttribute('role', 'combobox');
    dropdown.setAttribute('role', 'listbox');
});