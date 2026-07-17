<div class="dashboard-main-body">
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $lang->data['printer_settings'] ?? 'Printer Settings' }}</h5>
            <div id="qz-status-badge" wire:ignore>
                <span class="badge bg-danger-focus text-danger-600 px-16 py-8 radius-4 d-flex align-items-center gap-2">
                    <iconify-icon icon="clarity:error-standard-solid" class="text-lg"></iconify-icon>
                    Checking QZ Tray...
                </span>
            </div>
        </div>
        <div class="card-body">
            <!-- Connection Warning/Info Banner -->
            <div id="qz-warning-banner" class="alert alert-danger d-none radius-8 p-16 mb-24" role="alert" wire:ignore>
                <div class="d-flex align-items-start gap-3">
                    <iconify-icon icon="tabler:alert-triangle-filled" class="text-2xl text-danger-600"></iconify-icon>
                    <div>
                        <h6 class="text-md fw-bold text-danger-800 mb-4">QZ Tray Connection Failed</h6>
                        <p class="text-sm text-secondary-light mb-12">
                            Could not connect to QZ Tray. Please ensure that QZ Tray is installed and running on your
                            local machine.
                        </p>
                        <a href="https://qz.io/download/" target="_blank"
                            class="btn btn-danger btn-sm radius-8 px-16 py-8 d-inline-flex align-items-center gap-2">
                            <iconify-icon icon="material-symbols:download" class="text-md"></iconify-icon>
                            Download QZ Tray
                        </a>
                    </div>
                </div>
            </div>

            <div id="qz-success-banner" class="alert alert-success d-none radius-8 p-16 mb-24" role="alert" wire:ignore>
                <div class="d-flex align-items-start gap-3">
                    <iconify-icon icon="ep:success-filled" class="text-2xl text-success-600"></iconify-icon>
                    <div>
                        <h6 class="text-md fw-bold text-success-800 mb-4">Connected to QZ Tray</h6>
                        <p class="text-sm text-secondary-light mb-0">
                            WebSocket connection established successfully. Local printers are loaded and ready.
                        </p>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="row gy-4">
                    <!-- Invoice Printer Selection -->
                    <div class="col-md-6">
                        <div class="mb-20">
                            <label for="invoice_printer_select"
                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                {{ $lang->data['invoice_printer'] ?? 'Invoice Printer' }}
                            </label>
                            <div class="d-flex gap-2">
                                <select id="invoice_printer_select" class="form-select radius-8 form-control"
                                    wire:model="invoice_printer" wire:ignore>
                                    <option value="">Select Invoice Printer</option>
                                    @if ($invoice_printer)
                                        <option value="{{ $invoice_printer }}">{{ $invoice_printer }} (Saved)</option>
                                    @endif
                                </select>
                                <button type="button" onclick="testPrint('invoice')"
                                    class="btn btn-outline-primary radius-8 px-16 d-flex align-items-center gap-2">
                                    <iconify-icon icon="material-symbols:print-outline" class="text-lg"></iconify-icon>
                                    Test
                                </button>
                            </div>
                            @error('invoice_printer')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Cloth Tag Printer Selection -->
                    <div class="col-md-6">
                        <div class="mb-20">
                            <label for="tag_printer_select"
                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                {{ $lang->data['cloth_tag_printer'] ?? 'Cloth Tag Printer' }}
                            </label>
                            <div class="d-flex gap-2">
                                <select id="tag_printer_select" class="form-select radius-8 form-control"
                                    wire:model="cloth_tag_printer" wire:ignore>
                                    <option value="">Select Cloth Tag Printer</option>
                                    @if ($cloth_tag_printer)
                                        <option value="{{ $cloth_tag_printer }}">{{ $cloth_tag_printer }} (Saved)
                                        </option>
                                    @endif
                                </select>
                                <button type="button" onclick="testPrint('tag')"
                                    class="btn btn-outline-primary radius-8 px-16 d-flex align-items-center gap-2">
                                    <iconify-icon icon="material-symbols:print-outline" class="text-lg"></iconify-icon>
                                    Test
                                </button>
                            </div>
                            @error('cloth_tag_printer')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="col-12 d-flex align-items-center justify-content-end gap-3 mt-24">
                    <button type="submit"
                        class="btn btn-primary border border-primary-600 text-md px-24 py-12 radius-8">
                        {{ $lang->data['save'] ?? 'Save Printer Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('assets/js/lib/qz-tray.js') }}"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Configure QZ security promises if certificate is loaded
            qz.security.setCertificatePromise((resolve, reject) =>
                fetch('/qz/certificate').then(r => r.text()).then(resolve, reject));

            qz.security.setSignatureAlgorithm("SHA512");
            qz.security.setSignaturePromise(toSign => (resolve, reject) =>
                fetch('/qz/sign?request=' + encodeURIComponent(toSign))
                    .then(r => {
                        if (!r.ok) {
                            return r.text().then(text => {
                                throw new Error('Sign endpoint returned ' + r.status + ': ' + text);
                            });
                        }
                        return r.text();
                    })
                    .then(resolve, reject));

            // Initialize QZ Connection
            connectQZ();
        });

        function updateConnectionStatus(connected, errorMsg = '') {
            const badgeContainer = document.getElementById('qz-status-badge');
            const successBanner = document.getElementById('qz-success-banner');
            const warningBanner = document.getElementById('qz-warning-banner');

            if (connected) {
                badgeContainer.innerHTML = `
                <span class="badge bg-success-focus text-success-600 px-16 py-8 radius-4 d-flex align-items-center gap-2">
                    <iconify-icon icon="ep:success-filled" class="text-lg"></iconify-icon>
                    QZ Tray Connected
                </span>
            `;
                successBanner.classList.remove('d-none');
                warningBanner.classList.add('d-none');
            } else {
                badgeContainer.innerHTML = `
                <span class="badge bg-danger-focus text-danger-600 px-16 py-8 radius-4 d-flex align-items-center gap-2">
                    <iconify-icon icon="clarity:error-standard-solid" class="text-lg"></iconify-icon>
                    QZ Tray Disconnected
                </span>
            `;
                successBanner.classList.add('d-none');
                warningBanner.classList.remove('d-none');

                // Show alert message to the user about not installed or not running
                toastr.error('QZ Tray is not running or not installed. Please check status at the top.', 'QZ Tray Offline');
            }
        }

        function connectQZ() {
            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(function() {
                    updateConnectionStatus(true);
                    findPrinters();
                }).catch(function(err) {
                    console.error(err);
                    updateConnectionStatus(false, err);
                });
            } else {
                updateConnectionStatus(true);
                findPrinters();
            }
        }

        function findPrinters() {
            qz.printers.find().then(function(data) {
                const invoiceSelect = document.getElementById('invoice_printer_select');
                const tagSelect = document.getElementById('tag_printer_select');

                const savedInvoice = @js($invoice_printer);
                const savedTag = @js($cloth_tag_printer);

                // Rebuild selects preserving placeholder and current selections
                invoiceSelect.innerHTML = '<option value="">Select Invoice Printer</option>';
                tagSelect.innerHTML = '<option value="">Select Cloth Tag Printer</option>';

                data.forEach(function(printer) {
                    const opt1 = document.createElement('option');
                    opt1.value = printer;
                    opt1.text = printer;
                    invoiceSelect.appendChild(opt1);

                    const opt2 = document.createElement('option');
                    opt2.value = printer;
                    opt2.text = printer;
                    tagSelect.appendChild(opt2);
                });

                // Set values and dispatch change to trigger wire:model update
                if (data.includes(savedInvoice)) {
                    invoiceSelect.value = savedInvoice;
                    invoiceSelect.dispatchEvent(new Event('change'));
                }
                if (data.includes(savedTag)) {
                    tagSelect.value = savedTag;
                    tagSelect.dispatchEvent(new Event('change'));
                }
            }).catch(function(err) {
                console.error(err);
                toastr.error('Failed to query local printer list from QZ Tray: ' + err);
            });
        }

        function testPrint(type) {
            const printerSelectId = type === 'invoice' ? 'invoice_printer_select' : 'tag_printer_select';
            const printerName = document.getElementById(printerSelectId).value;

            if (!printerName) {
                toastr.warning('Please select a printer to test.', 'Warning');
                return;
            }

            if (!qz.websocket.isActive()) {
                toastr.error('Cannot print. QZ Tray is not connected.', 'Error');
                return;
            }

            const config = qz.configs.create(printerName);
            let printData = [];

            if (type === 'invoice') {
                printData = [{
                    type: 'html',
                    format: 'plain',
                    data: `
                    <html>
                        <div style="font-family: 'Courier New', Courier, monospace; width: 280px; padding: 10px; font-size: 12px;">
                            <div style="text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 8px;">
                                TEST PRINT SUCCESSFUL
                            </div>
                            <div style="text-align: center; margin-bottom: 12px;">
                                --- LaundryBox POS ---
                            </div>
                            <p style="margin: 4px 0;"><strong>Printer:</strong> ${printerName}</p>
                            <p style="margin: 4px 0;"><strong>Type:</strong> Invoice Printer</p>
                            <p style="margin: 4px 0;"><strong>Mode:</strong> HTML/PDF</p>
                            <p style="margin: 4px 0;"><strong>Status:</strong> Active</p>
                            <div style="border-top: 1px dashed #000; margin-top: 12px; padding-top: 8px; text-align: center;">
                                Thank you for using LaundryBox!
                            </div>
                        </div>
                    </html>
                `
                }];
            } else {
                printData = [{
                    type: 'html',
                    format: 'plain',
                    data: `
                    <html>
                        <div style="font-family: Arial, sans-serif; width: 180px; padding: 5px; font-size: 10px; border: 1px solid #000;">
                            <div style="font-weight: bold; text-align: center; border-bottom: 1px solid #000; margin-bottom: 4px;">
                                CLOTH TAG TEST
                            </div>
                            <p style="margin: 2px 0;"><strong>Printer:</strong> ${printerName}</p>
                            <p style="margin: 2px 0;"><strong>Mode:</strong> HTML/PDF</p>
                        </div>
                    </html>
                `
                }];
            }

            toastr.info('Sending test print payload to ' + printerName + '...');

            qz.print(config, printData).then(function() {
                toastr.success('Test print sent to ' + printerName + ' successfully!', 'Success');
            }).catch(function(err) {
                console.error(err);
                toastr.error('Test print failed: ' + err, 'Print Error');
            });
        }
    </script>
@endpush
