/**
 * Currency Formatter untuk Input Rupiah
 * Auto-format dengan separator ribuan (,)
 * Parse kembali ke integer untuk database
 */

// Format angka ke format rupiah (1,000,000)
function formatRupiah(angka) {
    if (!angka) return "";

    // Remove non-digit dan koma
    let number_string = angka.toString().replace(/[^\d]/g, "");

    // Jika kosong return empty
    if (!number_string) return "";

    // Format dengan separator ribuan
    return number_string.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Parse format rupiah ke integer
function parseRupiah(rupiah) {
    if (!rupiah) return 0;
    return parseInt(rupiah.toString().replace(/,/g, "")) || 0;
}

// Initialize currency input dengan Livewire
function initCurrencyInput(inputId, livewireProperty) {
    const input = document.getElementById(inputId);
    if (!input) return;

    // Format saat keyup dengan delay
    let timeout = null;
    input.addEventListener("keyup", function (e) {
        // Skip jika tombol navigasi
        if (
            ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Tab"].includes(
                e.key,
            )
        ) {
            return;
        }

        // Simpan posisi cursor
        let cursorPosition = this.selectionStart;
        let oldLength = this.value.length;
        let oldValue = this.value;

        // Format value
        let formatted = formatRupiah(this.value);

        // Hitung pergeseran karena koma
        let newLength = formatted.length;
        let diff = newLength - oldLength;

        // Set formatted value
        this.value = formatted;

        // Restore cursor position dengan adjustment
        let newPosition = cursorPosition + diff;
        this.setSelectionRange(newPosition, newPosition);
    });

    // Parse saat blur (update Livewire)
    input.addEventListener("blur", function () {
        let parsed = parseRupiah(this.value);

        // Update Livewire property
        if (livewireProperty && window.Livewire) {
            // Get component
            let component = window.Livewire.find(
                this.closest("[wire\\:id]")?.getAttribute("wire:id"),
            );

            if (component) {
                component.set(livewireProperty, parsed);
            }
        }
    });

    // Format initial value jika ada
    if (input.value) {
        input.value = formatRupiah(input.value);
    }
}

// Auto-init semua input dengan class 'currency-input'
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".currency-input").forEach(function (input) {
        const property =
            input.getAttribute("data-property") ||
            input.getAttribute("wire:model");
        if (property) {
            initCurrencyInput(input.id, property);
        }
    });
});

// Re-init after Livewire updates
if (window.Livewire) {
    document.addEventListener("livewire:navigated", function () {
        document.querySelectorAll(".currency-input").forEach(function (input) {
            const property =
                input.getAttribute("data-property") ||
                input.getAttribute("wire:model");
            if (property) {
                initCurrencyInput(input.id, property);
            }
        });
    });
}
