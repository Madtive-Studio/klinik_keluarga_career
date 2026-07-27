<div class="candidate-empty-state text-center border rounded p-4 p-md-5 mb-3">
	<div class="candidate-empty-state__icon mx-auto mb-3">
		<i class="mdi {{ $icon ?? 'mdi-file-document-outline' }}"></i>
	</div>
	<h6 class="text-dark mb-2">{{ $title ?? __('common.no_data') }}</h6>
	@if (!empty($description))
		<p class="text-muted {{ !empty($actionUrl) ? 'mb-3' : 'mb-0' }}">{{ $description }}</p>
	@endif
	@if (!empty($actionUrl) && !empty($actionLabel))
		<a href="{{ $actionUrl }}" class="btn btn-primary btn-sm mt-3">
			<i class="mdi mdi-upload me-1"></i>{{ $actionLabel }}
		</a>
	@endif
</div>

<style>
	.candidate-empty-state {
		background: #f8f9fc;
		border: 1px dashed #d6deef !important;
	}

	.candidate-empty-state__icon {
		width: 72px;
		height: 72px;
		border-radius: 50%;
		background: rgba(47, 85, 212, 0.1);
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.candidate-empty-state__icon i {
		font-size: 36px;
		color: #2f55d4;
		line-height: 1;
	}
</style>
