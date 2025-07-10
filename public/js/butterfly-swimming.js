// Custom JavaScript for Butterfly Swimming Course Admin

// Confirm Delete
function confirmDelete(formId) {
  if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
    document.getElementById(formId).submit()
  }
}

// Image Preview
function previewImage(input, previewId) {
  if (input.files && input.files[0]) {
    var reader = new FileReader()

    reader.onload = (e) => {
      document.getElementById(previewId).src = e.target.result
    }

    reader.readAsDataURL(input.files[0])
  }
}

// Format Currency
function formatCurrency(input) {
  // Remove non-digit characters
  let value = input.value.replace(/\D/g, "")

  // Format with thousand separator
  value = new Intl.NumberFormat("id-ID").format(value)

  // Update the input value
  input.value = value
}

// Toggle Password Visibility
function togglePasswordVisibility(inputId, iconId) {
  const passwordInput = document.getElementById(inputId)
  const icon = document.getElementById(iconId)

  if (passwordInput.type === "password") {
    passwordInput.type = "text"
    icon.classList.remove("fa-eye")
    icon.classList.add("fa-eye-slash")
  } else {
    passwordInput.type = "password"
    icon.classList.remove("fa-eye-slash")
    icon.classList.add("fa-eye")
  }
}

// Initialize DataTables
const $ = window.jQuery // Declare the $ variable
$(document).ready(() => {
  if ($.fn.dataTable) {
    $(".datatable").DataTable({
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        zeroRecords: "Tidak ada data yang ditemukan",
        info: "Menampilkan halaman _PAGE_ dari _PAGES_",
        infoEmpty: "Tidak ada data yang tersedia",
        infoFiltered: "(difilter dari _MAX_ total data)",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya",
        },
      },
    })
  }

  // Initialize Tooltips
  $('[data-toggle="tooltip"]').tooltip()

  // Initialize Popovers
  $('[data-toggle="popover"]').popover()

      // --- START Fix for Native Time Input (Tambahkan kode ini) ---
    // Kode ini dijalankan setelah DOM siap dan setelah inisialisasi SB Admin 2.
    $('input[type="time"]').each(function() {
        var $this = $(this); // Cache objek jQuery

        // Hapus semua event listener 'click' dan 'focus' yang mungkin ditambahkan
        // oleh sb-admin-2.min.js atau script lain pada elemen ini.
        // Ini adalah langkah kunci untuk mengatasi intervensi.
        $this.off('click');
        $this.off('focus');

        // Tambahkan event listener 'click' kustom kita
        $this.on('click', function(e) {
            // Mencegah perilaku default browser dan menghentikan propagasi event
            // untuk memastikan hanya handler kita yang berjalan.
            e.preventDefault();
            e.stopPropagation();

            // Coba untuk menampilkan picker waktu native menggunakan API modern
            if (typeof this.showPicker === 'function') {
                this.showPicker();
            } else {
                // Fallback untuk browser lama atau jika showPicker tidak didukung:
                // mencoba memfokuskan input, yang kadang bisa memicu picker native.
                $(this).focus();
            }
        });

        // Tambahkan event listener 'focus' kustom untuk konsistensi
        // (misalnya, jika pengguna menekan tombol Tab untuk berpindah ke input ini)
        $this.on('focus', function() {
            if (typeof this.showPicker === 'function') {
                this.showPicker();
            }
        });
    });
    // --- END Fix for Native Time Input ---
})

// Notification System
class NotificationSystem {
  constructor() {
    this.container = document.getElementById("notification-container")
    if (!this.container) {
      this.container = document.createElement("div")
      this.container.id = "notification-container"
      this.container.style.position = "fixed"
      this.container.style.top = "20px"
      this.container.style.right = "20px"
      this.container.style.zIndex = "9999"
      document.body.appendChild(this.container)
    }
  }

  show(message, type = "info", duration = 5000) {
    const notification = document.createElement("div")
    notification.className = `alert alert-${type} alert-dismissible fade show`
    notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `

    this.container.appendChild(notification)

    // Auto dismiss after duration
    setTimeout(() => {
      $(notification).alert("close")
    }, duration)

    return notification
  }

  success(message, duration = 5000) {
    return this.show(message, "success", duration)
  }

  error(message, duration = 5000) {
    return this.show(message, "danger", duration)
  }

  warning(message, duration = 5000) {
    return this.show(message, "warning", duration)
  }

  info(message, duration = 5000) {
    return this.show(message, "info", duration)
  }
}

// Initialize Notification System
const notifications = new NotificationSystem()

// Example usage:
// notifications.success('Data berhasil disimpan!');
// notifications.error('Terjadi kesalahan saat menyimpan data.');
// notifications.warning('Peringatan: Data belum lengkap.');
// notifications.info('Informasi: Sistem akan diperbarui pada tanggal 1 Juni 2023.');
