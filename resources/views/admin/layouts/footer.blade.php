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
$(function () {
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
    const $startEl = $('.flatpickr-datetime[name="' + startName + '"]');
    const $endEl = $('.flatpickr-datetime[name="' + endName + '"]');
    if (!$startEl.length || !$endEl.length) return;

    const startEl = $startEl[0];
    const endEl = $endEl[0];

    const getMinDate = function (el) {
      if (el.dataset.minDate === 'today') {
        return new Date();
      }
      return el.dataset.minDate || null;
    };

    let startPicker;
    let endPicker;

    const endOptions = {
      onOpen: function () {
        if (startPicker && startPicker.selectedDates[0]) {
          endPicker.set('minDate', new Date(startPicker.selectedDates[0].getTime() + 60000));
        } else if (endEl.dataset.minDate === 'today') {
          endPicker.set('minDate', new Date());
        }
      }
    };
    const endMinDate = getMinDate(endEl);
    if (endMinDate) {
      endOptions.minDate = endMinDate;
    }

    endPicker = initFlatpickr(endEl, endOptions);
    const startOptions = {
      onOpen: function () {
        if (startEl.dataset.minDate === 'today') {
          startPicker.set('minDate', new Date());
        }
      },
      onChange: function (selectedDates) {
        if (selectedDates[0]) {
          const nextMin = new Date(selectedDates[0].getTime() + 60000);
          endPicker.set('minDate', nextMin);
          if (endPicker.selectedDates[0] && endPicker.selectedDates[0] <= selectedDates[0]) {
            endPicker.clear();
          }
        } else {
          const defaultMin = getMinDate(endEl);
          endPicker.set('minDate', defaultMin);
        }
      },
    };

    const startMinDate = getMinDate(startEl);
    if (startMinDate) {
      startOptions.minDate = startMinDate;
    }

    startPicker = initFlatpickr(startEl, startOptions);

    if (startPicker.selectedDates[0]) {
      endPicker.set('minDate', new Date(startPicker.selectedDates[0].getTime() + 60000));
    } else if (endMinDate) {
      endPicker.set('minDate', endMinDate);
    }
  });

  $('.flatpickr-datetime').each(function () {
    if (linkedNames.has(this.name)) return;
    initFlatpickr(this);
  });

  // Admin global search
  const $input = $('#admin-global-search');
  const $resultsBox = $('#admin-global-search-results');
  if (!$input.length || !$resultsBox.length) return;

  let timer = null;

  const renderResults = (items) => {
    if (!items.length) {
      $resultsBox.html('<div class="dropdown-item text-muted">{{ __('admin.navbar.no_results') }}</div>').show();
      return;
    }

    const html = items.map(item => `
      <a href="${item.url}" class="dropdown-item">
        <span class="badge bg-label-primary me-2">${item.type}</span>${item.label}
      </a>
    `).join('');
    $resultsBox.html(html).show();
  };

  $input.on('input', function () {
    clearTimeout(timer);
    const q = $(this).val().trim();
    if (q.length < 2) {
      $resultsBox.hide();
      return;
    }

    timer = setTimeout(function () {
      $.ajax({
        url: '{{ route('admin.search') }}',
        data: { q: q },
        dataType: 'json'
      }).done(function (data) {
        renderResults(data.results || []);
      });
    }, 250);
  });

  $(document).on('click', function (event) {
    if (!$input.is(event.target) && !$resultsBox.is(event.target) && !$resultsBox.has(event.target).length) {
      $resultsBox.hide();
    }
  });

  $(document).on('keydown', function (event) {
    if ((event.ctrlKey || event.metaKey) && event.key === '/') {
      event.preventDefault();
      $input.trigger('focus');
    }
  });
});
</script>
<script src="{{ asset('assets/admin/assets/js/dashboards-analytics.js') }}"></script>
@yield('js')
@endsection