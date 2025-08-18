@extends('layouts.app')

@section('page_title', 'Kasir')

@section('content')
<div class="row">
    {{-- KOLOM KIRI: Produk --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Produk</h4>

                {{-- FORM FILTER --}}
                <form id="filter-form" method="GET" action="{{ route('kasir') }}" class="row g-2 align-items-center mb-3">
                    <div class="col-sm-6">
                        <input type="text"
                            id="search-product"
                            name="q"
                            class="form-control"
                            placeholder="Cari nama…"
                            value="{{ $q ?? '' }}"
                            autocomplete="off">
                    </div>

                    <div class="col-sm-3">
                        <select id="category-filter" name="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)($category ?? '') === (string)$cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <select id="sort-products" name="sort" class="form-control">
                            <option value="name-asc" {{ ($sort ?? '')==='name-asc'   ? 'selected' : '' }}>Nama A→Z</option>
                            <option value="name-desc" {{ ($sort ?? '')==='name-desc'  ? 'selected' : '' }}>Nama Z→A</option>
                            <option value="price-asc" {{ ($sort ?? '')==='price-asc'  ? 'selected' : '' }}>Harga termurah</option>
                            <option value="price-desc" {{ ($sort ?? '')==='price-desc' ? 'selected' : '' }}>Harga termahal</option>
                            <option value="stock-desc" {{ ($sort ?? '')==='stock-desc' ? 'selected' : '' }}>Stok terbanyak</option>
                            <option value="stock-asc" {{ ($sort ?? '')==='stock-asc'  ? 'selected' : '' }}>Stok tersedikit</option>
                            <option value="newest" {{ ($sort ?? '')==='newest'     ? 'selected' : '' }}>Terbaru</option>
                        </select>
                    </div>

                    <div class="col-sm-1">
                        <button type="submit"
                            class="btn btn-primary btn-icon rounded-circle"
                            id="btn-search"
                            aria-label="Cari"
                            title="Cari">
                            <i class="ti-search"></i>
                        </button>
                    </div>
                </form>

                {{-- DAFTAR PRODUK (SCROLL VERTIKAL) --}}
                <div id="product-scroll" class="mb-2">
                    <div id="product-list" class="product-grid">
                        @foreach($products as $product)
                        <div class="product-card">
                            <div class="product-item card h-100"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->selling_price }}"
                                data-stock="{{ $product->stock }}">
                                <div class="card-body d-flex flex-column">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="img-fluid rounded mb-3"
                                        style="height:150px;object-fit:cover;width:100%;">
                                    @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded mb-3"
                                        style="height:150px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <path d="M21 15l-5-5L5 21" />
                                        </svg>
                                    </div>
                                    @endif

                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <p class="card-text">Rp{{ number_format($product->selling_price) }}</p>
                                    <p class="card-text"><small class="text-muted">Stok: {{ $product->stock }}</small></p>
                                    <button class="btn btn-primary btn-sm add-to-cart-btn mt-auto">Tambah ke Keranjang</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-2">
                    {{ $products->appends(request()->only('q','sort','category'))->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Keranjang --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <div id="cart-container">
                    <div class="text-center text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h7.72a2 2 0 0 0 2-1.61L21 6H6" />
                        </svg>
                        <p>Keranjang belanja kosong</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-6"><strong>Total:</strong></div>
                    <div class="col-6 text-right"><span id="total-amount">Rp0</span></div>
                </div>

                <div class="form-group mt-3">
                    <label>Uang Tunai:</label>
                    <input type="number" id="total-paid" class="form-control" placeholder="0">
                </div>

                <div class="row">
                    <div class="col-6"><strong>Kembalian:</strong></div>
                    <div class="col-6 text-right"><span id="change-amount">Rp0</span></div>
                </div>

                {{-- keypad --}}
                <div class="row mt-3">
                    @for($i=1;$i<=9;$i++)
                        <div class="col-4 mb-2">
                        <button class="btn btn-outline-secondary btn-block number-btn" data-value="{{ $i }}">{{ $i }}</button>
                </div>
                @if($i%3===0)<div class="w-100"></div>@endif
                @endfor
                <div class="col-4">
                    <button class="btn btn-outline-secondary btn-block number-btn" data-value="0">0</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-secondary btn-block" id="clear-payment">C</button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-secondary btn-block" id="exact-payment">=</button>
                </div>
            </div>

            <button class="btn btn-success btn-block mt-3" id="pay-button">Bayar</button>
        </div>
    </div>
</div>
</div>

{{-- STYLE: scroll vertikal + grid --}}
<style>
    #product-scroll {
        max-height: 68vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: .25rem;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }

    .product-card {
        display: flex;
    }

    #product-scroll::-webkit-scrollbar {
        width: 8px;
    }

    #product-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, .2);
        border-radius: 8px;
    }

    @media (max-width: 991.98px) {
        #product-scroll {
            max-height: 50vh;
        }
    }
</style>

{{-- SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Filter form ---
        const form = document.getElementById('filter-form');
        const q = document.getElementById('search-product');
        const sort = document.getElementById('sort-products');
        const cat = document.getElementById('category-filter');
        sort?.addEventListener('change', () => form.submit());
        cat?.addEventListener('change', () => form.submit());
        q?.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.submit();
            }
        });

        // --- Keranjang ---
        const productList = document.getElementById('product-list');
        const cartContainer = document.getElementById('cart-container');
        const totalAmountEl = document.getElementById('total-amount');
        const totalPaidEl = document.getElementById('total-paid');
        const changeAmountEl = document.getElementById('change-amount');

        let cart = [];
        const nf = v => new Intl.NumberFormat('id-ID').format(v);
        const parseNumber = t => parseInt((t || '').toString().replace(/[^0-9]/g, '')) || 0;

        const NotificationSystem = window.NotificationSystem || {
            show: (type, msg) => alert((type || 'info').toUpperCase() + ': ' + msg),
            showSuccess: (title, msg) => alert((title || 'Sukses') + ': ' + msg),
        };
        const showNotification = (type, message, title) => NotificationSystem.show(type, message, title);
        const showSuccessModal = (message, kembalian) => NotificationSystem.showSuccess('Transaksi Berhasil', `${message}\nKembalian: ${kembalian}`);

        const updateChange = () => {
            const totalAmount = parseNumber(totalAmountEl.textContent);
            const totalPaid = parseInt(totalPaidEl.value) || 0;
            const change = totalPaid - totalAmount;
            changeAmountEl.textContent = `Rp${nf(change)}`;
        };

        const updateCart = () => {
            if (cart.length === 0) {
                cartContainer.innerHTML = `
                <div class="text-center text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="display:inline-block;vertical-align:-.2em;background:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 12.39a2 2 0 0 0 2 1.61h7.72a2 2 0 0 0 2-1.61L21 6H6"/></svg>
                    <p>Keranjang belanja kosong</p>
                </div>`;
                totalAmountEl.textContent = 'Rp0';
                return;
            }

            let html = '',
                total = 0;
            cart.forEach(it => {
                const subtotal = it.price * it.quantity;
                total += subtotal;
                html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <strong>${it.name}</strong><br>
                    <small class="text-muted">Rp${nf(it.price)} x ${it.quantity}</small>
                </div>
                <div class="text-right">
                    <div>Rp${nf(subtotal)}</div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary btn-decrease-quantity" data-id="${it.id}">-</button>
                        <button class="btn btn-outline-secondary btn-increase-quantity" data-id="${it.id}">+</button>
                        <button class="btn btn-outline-danger remove-from-cart" data-id="${it.id}">Hapus</button>
                    </div>
                </div>
            </div>`;
            });
            cartContainer.innerHTML = html;
            totalAmountEl.textContent = `Rp${nf(total)}`;
            updateChange();
        };

        const addProductToCart = p => {
            const exist = cart.find(i => i.id == p.id);
            if (exist) {
                if (exist.quantity < p.stock) exist.quantity++;
                else showNotification('warning', 'Stok produk tidak mencukupi.');
            } else {
                if (p.stock > 0) cart.push({
                    id: p.id,
                    name: p.name,
                    price: p.price,
                    quantity: 1,
                    stock: p.stock
                });
                else showNotification('error', 'Stok produk habis.');
            }
            updateCart();
        };

        productList?.addEventListener('click', e => {
            const btn = e.target.closest('.add-to-cart-btn');
            if (!btn) return;
            const card = btn.closest('.product-item');
            addProductToCart({
                id: card.dataset.id,
                name: card.dataset.name,
                price: parseInt(card.dataset.price),
                stock: parseInt(card.dataset.stock),
            });
        });

        cartContainer.addEventListener('click', e => {
            const dec = e.target.closest('.btn-decrease-quantity');
            const inc = e.target.closest('.btn-increase-quantity');
            const del = e.target.closest('.remove-from-cart');

            if (dec) {
                const it = cart.find(i => i.id == dec.dataset.id);
                if (it && it.quantity > 1) it.quantity--;
            }
            if (inc) {
                const it = cart.find(i => i.id == inc.dataset.id);
                if (it && it.quantity < it.stock) it.quantity++;
                else if (it) showNotification('warning', 'Stok produk tidak mencukupi.');
            }
            if (del) {
                const idx = cart.findIndex(i => i.id == del.dataset.id);
                if (idx > -1) cart.splice(idx, 1);
            }
            updateCart();
        });

        totalPaidEl.addEventListener('input', updateChange);

        document.addEventListener('click', e => {
            const num = e.target.closest('.number-btn');
            const clr = e.target.closest('#clear-payment');
            const eq = e.target.closest('#exact-payment');
            if (num) {
                totalPaidEl.value = (totalPaidEl.value || '') + num.dataset.value;
                updateChange();
            }
            if (clr) {
                totalPaidEl.value = '';
                updateChange();
            }
            if (eq) {
                totalPaidEl.value = parseNumber(totalAmountEl.textContent);
                updateChange();
            }
        });

        /**
         * Sisipkan tombol "Cetak Struk" ke modal sukses.
         * Mencoba beberapa lokasi dan retry sebentar jika modal belum siap.
         */
        function attachReceiptButton(transactionId) {
            const makeButton = () => {
                const a = document.createElement('a');
                a.href = `/struk/${transactionId}`;
                a.target = '_blank';
                a.className = 'btn btn-info btn-sm mt-2';
                a.setAttribute('data-role', 'print-receipt');
                a.innerHTML = '<i class="ti-printer mr-1"></i> Cetak Struk';
                return a;
            };

            const tryInsert = () => {
                const candidates = [
                    document.getElementById('successDetails'),
                    document.querySelector('#notify-success .modal-body'),
                    document.querySelector('.modal.show .modal-body'),
                    document.querySelector('.modal .modal-body')
                ].filter(Boolean);

                for (const host of candidates) {
                    // hindari duplikasi
                    host.querySelector('[data-role="print-receipt"]')?.remove();
                    host.appendChild(makeButton());
                    return true;
                }
                return false;
            };

            // coba segera, jika belum ketemu retry beberapa kali (modal mungkin render async)
            if (tryInsert()) return;
            let tries = 10;
            const timer = setInterval(() => {
                if (tryInsert() || --tries <= 0) clearInterval(timer);
            }, 100);
        }

        document.getElementById('pay-button').addEventListener('click', async () => {
            if (cart.length === 0) return showNotification('warning', 'Keranjang belanja kosong.');
            const totalAmount = parseNumber(totalAmountEl.textContent);
            const totalPaid = parseInt(totalPaidEl.value) || 0;
            if (totalPaid < totalAmount) return showNotification('error', 'Uang yang dibayarkan tidak mencukupi.');

            const cartItems = cart.map(i => ({
                product_id: i.id,
                quantity: i.quantity,
                price: i.price
            }));

            try {
                const res = await fetch('{{ route("kasir.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart_items: cartItems,
                        total_paid: totalPaid
                    })
                });

                const result = await res.json();
                if (res.ok) {
                    showSuccessModal('Transaksi berhasil diproses!', 'Rp' + nf(result.transaction.change_amount));
                    // >>> Tambahkan tombol Cetak Struk pada pop-up
                    attachReceiptButton(result.transaction.id);

                    cart = [];
                    updateCart();
                    totalPaidEl.value = '';
                    updateChange();
                } else {
                    showNotification('error', result.message || 'Gagal memproses transaksi.');
                }
            } catch (err) {
                console.error(err);
                showNotification('error', 'Terjadi kesalahan saat memproses transaksi.');
            }
        });
    });
</script>
@endsection
