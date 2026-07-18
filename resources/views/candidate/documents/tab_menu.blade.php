@php
	$documentTypes = $documentTypes ?? \App\Enums\DocumentType::getWithLabels();
	$queryParams = request()->only('per_page');
@endphp
<div class="col-lg-3 col-md-7">
	<div class="job-detail border rounded p-2">
		<div class="job-detail-content">
			<nav class="nav nav-pills flex-column">
				<a class="flex-sm-fill nav-link {{
					request()->routeIs('candidate.my.documents.index') && empty(request('type')) ? 'active' : ''
				}}" href="{{ route('candidate.my.documents.index', $queryParams) }}">{{ __('candidate.documents.tab_all') }}</a>

				@foreach ($documentTypes as $value => $label)
					<a class="flex-sm-fill nav-link document-type-drop {{
						request()->routeIs('candidate.my.documents.index') && request('type') === $value ? 'active' : ''
					}}"
						href="{{ route('candidate.my.documents.index', array_merge($queryParams, ['type' => $value])) }}"
						data-type="{{ $value }}"
						data-label="{{ $label }}">
						<span class="document-type-drop__label">{{ $label }}</span>
						<small class="document-type-drop__hint text-muted d-none">{{ __('candidate.documents.sidebar_drop_hint') }}</small>
					</a>
				@endforeach
			</nav>
		</div>
	</div>
</div>
