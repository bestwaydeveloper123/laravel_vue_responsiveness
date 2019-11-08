<div class="sidebar">
	<nav class="sidebar-nav">
		<ul class="nav">
			<li class="nav-item">
				<a class="nav-link" href="index.html">
					<i class="nav-icon icon-speedometer"></i> Dashboard
					<span class="badge badge-primary">NEW</span>
				</a>
			</li>
			<li class="nav-title">Menu</li>
			<li class="nav-item nav-dropdown">
				<a class="nav-link nav-dropdown-toggle pt-0" href="#">
					<a class="nav-link" href="/"><i class="nav-icon icon-note"></i> Accounts</a></a>
				<ul class="nav-dropdown-items">
					<li class="nav-item">
						<a class="nav-link" href="/quotes">
							<i class="nav-icon icon-note"></i> Quotes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/print">
							<i class="nav-icon icon-printer"></i> Mass Print</a>
					</li>
				</ul>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="/">
					<i class="nav-icon icon-note"></i> Accounts</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/quotes">
					<i class="nav-icon icon-note"></i> Quotes</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/print">
					<i class="nav-icon icon-printer"></i> Mass Print</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="/admin/inventory/index">
					<i class="nav-icon icon-note"></i> Inventory</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/admin/core/index">
					<i class="nav-icon icon-note"></i> Core</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#" data-toggle="modal" data-target="#check-adminer">
					<i class="nav-icon icon-notebook"></i> Reports</a>
			</li>
			<li class="nav-item nav-dropdown">
				<a class="nav-link nav-dropdown-toggle" href="#">
					<i class="nav-icon icon-user"></i> Admin</a>
				<ul class="nav-dropdown-items">
					<li class="nav-item">
						<a class="nav-link" href="/admin/inventory/create">
							<i class="nav-icon icon-plus"></i> Add Inventory</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/core">
							<i class="nav-icon icon-plus"></i> Add Core</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/inventory/bulkaddaccounts">
							<i class="nav-icon icon-plus"></i> Bulk Add Accounts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/inventory/ebayandwebsitebreakdown">
							<i class="nav-icon icon-plus"></i> Ebay & Website Breakdown</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/inventory/bulkcheckinventory">
							<i class="nav-icon icon-check"></i> Bulk Check Inventory</a>
					</li>
				</ul>
			</li>
			<li class="nav-item nav-dropdown">
				<a class="nav-link nav-dropdown-toggle" href="#">
					<i class="nav-icon icon-frame"></i> Scanner</a>
				<ul class="nav-dropdown-items">
					<li class="nav-item">
						<a class="nav-link" href="/admin/scanner">
							<i class="nav-icon icon-frame"></i>
							Admin
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/shipping">
							<i class="nav-icon icon-plus"></i> Shipping</a>
					</li>
				</ul>
			</li>
			<li class="nav-item nav-dropdown">
				<a class="nav-link nav-dropdown-toggle" href="#">
					<i class="nav-icon icon-chart"></i>
					Stats
				</a>
				<ul class="nav-dropdown-items">
					<li class="nav-item">
						<a class="nav-link" href="/admin/points/accountmanager">
							<i class="nav-icon icon-plus"></i>
							Account Manager
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" data-toggle="modal" data-target="#check-programmer">
							<i class="nav-icon icon-plus"></i>
							Programmer
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/admin/points/shipping">
							<i class="nav-icon icon-plus"></i>
							Shipping
						</a>
					</li>
				</ul>
      </li>
      <li class="nav-item nav-dropdown">
				<a class="nav-link" href="/admin/chat/index">
					<i class="nav-icon icon-bubbles"></i>
					Chat with Customer
				</a>
			</li>
			<li class="nav-item mt-auto">
				<a class="nav-link nav-link-success" href="/admin/patchnotes" target="_top">
					<i class="nav-icon icon-note"></i>
					<strong>Patch Notes</strong>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link nav-link-success" href="http://10.248.0.3" target="_top">
					<i class="nav-icon icon-layers"></i>
					<strong>Hydra</strong>
				</a>
			</li>
		</ul>
	</nav>
	<button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>

@include('common._modal', ['action' => 'adminer'])
@include('common._modal', ['action' => 'programmer'])
