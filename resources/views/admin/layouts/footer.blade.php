<footer class="content-footer footer bg-footer-theme">
  <div class="container-fluid">
    <div
      class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        ©
        2026 -
        <script>
          document.write(new Date().getFullYear());
        </script>
        , made with ❤️ by <a href="https://madtive.com" target="_blank" class="footer-link">Madtive Studio</a>
      </div>
    </div>
  </div>
</footer>

@section('scripts')
<script src="{{ asset('assets/admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/admin/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script src="{{ asset('assets/admin/assets/js/main.js') }}"></script>
<script>
window.adminI18n = @json(__('admin.js'));
document.addEventListener('DOMContentLoaded', function () {
  const flatpickrOptions = {
    enableTime: true,
    enableSeconds: true,
    time_24hr: true,
    dateFormat: 'd-m-Y H:i:S',
    allowInput: true,
  };

  const linkedPairs = [
    ['start_date', 'end_date'],
    ['start_datetime', 'end_datetime'],
  ];
  const linkedNames = new Set(linkedPairs.flat());

  function initFlatpickr(element, options = {}) {
    return flatpickr(element, { ...flatpickrOptions, ...options });
  }

  linkedPairs.forEach(function ([startName, endName]) {
    const startEl = document.querySelector('.flatpickr-datetime[name="' + startName + '"]');
    const endEl = document.querySelector('.flatpickr-datetime[name="' + endName + '"]');
    if (!startEl || !endEl) return;

    const endPicker = initFlatpickr(endEl);
    const startPicker = initFlatpickr(startEl, {
      onChange: function (selectedDates) {
        const minDate = selectedDates[0] || null;
        endPicker.set('minDate', minDate);

        if (endPicker.selectedDates[0] && minDate && endPicker.selectedDates[0] <= minDate) {
          endPicker.clear();
        }
      },
    });

    if (startPicker.selectedDates[0]) {
      endPicker.set('minDate', startPicker.selectedDates[0]);
    }
  });

  document.querySelectorAll('.flatpickr-datetime').forEach(function (element) {
    if (linkedNames.has(element.name)) return;
    initFlatpickr(element);
  });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('admin-global-search');
  const resultsBox = document.getElementById('admin-global-search-results');
  if (!input || !resultsBox) return;

  let timer = null;

  const renderResults = (items) => {
    if (!items.length) {
      resultsBox.innerHTML = '<div class="dropdown-item text-muted">{{ __('admin.navbar.no_results') }}</div>';
      resultsBox.style.display = 'block';
      return;
    }

    resultsBox.innerHTML = items.map(item => `
      <a href="${item.url}" class="dropdown-item">
        <span class="badge bg-label-primary me-2">${item.type}</span>${item.label}
      </a>
    `).join('');
    resultsBox.style.display = 'block';
  };

  input.addEventListener('input', function () {
    clearTimeout(timer);
    const q = this.value.trim();
    if (q.length < 2) {
      resultsBox.style.display = 'none';
      return;
    }

    timer = setTimeout(async () => {
      const response = await fetch(`{{ route('admin.search') }}?q=${encodeURIComponent(q)}`);
      const data = await response.json();
      renderResults(data.results || []);
    }, 250);
  });

  document.addEventListener('click', function (event) {
    if (!input.contains(event.target) && !resultsBox.contains(event.target)) {
      resultsBox.style.display = 'none';
    }
  });

  document.addEventListener('keydown', function (event) {
    if ((event.ctrlKey || event.metaKey) && event.key === '/') {
      event.preventDefault();
      input.focus();
    }
  });
});
</script>
<script src="{{ asset('assets/admin/assets/js/dashboards-analytics.js') }}"></script>
@yield('js')
@endsection