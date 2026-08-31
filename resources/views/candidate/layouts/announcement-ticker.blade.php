<div id="topAnnouncementTicker" class="top-announcement-ticker" style="background: linear-gradient(90deg, #0d5c58 0%, #134e4a 50%, #0f766e 100%); color: #ffffff; font-size: 12.5px; z-index: 1050; position: relative; border-bottom: 1px solid rgba(255,255,255,0.12); display: block;">
	<div class="container-fluid px-3 px-md-4">
		<div class="d-flex align-items-center justify-content-between py-1 py-md-1 gap-2">
			<!-- Announcement Badge -->
			<div class="d-flex align-items-center flex-shrink-0 gap-2">
				<span class="badge bg-warning text-dark fw-bold px-2 py-1 shadow-xs d-flex align-items-center gap-1" style="font-size: 11px; letter-spacing: 0.5px;">
					<span class="ticker-pulse-dot"></span>
					<i class="mdi mdi-bullhorn-outline me-1"></i>{{ __('candidate.ticker.badge') }}
				</span>
			</div>

			<!-- Marquee / Ticker Content -->
			<div class="ticker-content-wrapper flex-grow-1 overflow-hidden position-relative mx-2" style="height: 22px;">
				<div class="ticker-content-track">
					<span class="ticker-item">
						<i class="mdi mdi-shield-alert-outline text-warning me-1"></i>
						<strong>{{ __('candidate.ticker.notice_1') }}</strong>
					</span>
					<span class="ticker-separator text-white-50 mx-3">•</span>
					<span class="ticker-item">
						<i class="mdi mdi-file-check-outline text-info me-1"></i>
						{{ __('candidate.ticker.notice_2') }}
					</span>
					<span class="ticker-separator text-white-50 mx-3">•</span>
					<span class="ticker-item">
						<i class="mdi mdi-email-check-outline text-success me-1"></i>
						{{ __('candidate.ticker.notice_3') }}
					</span>
				</div>
			</div>

			<!-- Dismiss Button -->
			<div class="flex-shrink-0 d-flex align-items-center">
				<button type="button" id="btnDismissTicker" class="btn btn-link text-white-50 p-0 text-decoration-none hover-white" title="Tutup Pengumuman" style="font-size: 16px; line-height: 1;">
					<i class="mdi mdi-close"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<style>
	.ticker-pulse-dot {
		display: inline-block;
		width: 6px;
		height: 6px;
		background-color: #dc2626;
		border-radius: 50%;
		animation: pulseAnimation 1.5s infinite;
	}
	@keyframes pulseAnimation {
		0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
		70% { transform: scale(1.2); box-shadow: 0 0 0 5px rgba(220, 38, 38, 0); }
		100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
	}
	.ticker-content-track {
		display: inline-block;
		white-space: nowrap;
		animation: marqueeScroll 32s linear infinite;
	}
	.ticker-content-track:hover {
		animation-play-state: paused;
	}
	@keyframes marqueeScroll {
		0% { transform: translateX(100%); }
		100% { transform: translateX(-100%); }
	}
	.hover-white:hover {
		color: #ffffff !important;
	}
	@media (max-width: 576px) {
		.ticker-content-wrapper {
			font-size: 11.5px;
		}
		.ticker-content-track {
			animation-duration: 22s;
		}
	}
</style>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const ticker = document.getElementById("topAnnouncementTicker");
		const dismissBtn = document.getElementById("btnDismissTicker");
		if (sessionStorage.getItem("hideAnnouncementTicker") === "true") {
			if (ticker) ticker.style.display = "none";
		}
		if (dismissBtn) {
			dismissBtn.addEventListener("click", function() {
				if (ticker) ticker.style.display = "none";
				sessionStorage.setItem("hideAnnouncementTicker", "true");
			});
		}
	});
</script>
