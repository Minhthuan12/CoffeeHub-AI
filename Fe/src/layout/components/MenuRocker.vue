<template>
	<div class="nav-container primary-menu px-0">
		<nav class="navbar navbar-expand-xl w-100">
			<ul class="navbar-nav justify-content-start flex-grow-1 gap-1">
				<li class="nav-item">
					<router-link class="nav-link" to="/admin/dashboard/default">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div>
						<div class="menu-title">Dashboard</div>
					</router-link>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class="fa-solid fa-circle-user"></i>
						</div>
						<div class="menu-title">Quản lý người dùng</div>
					</a>
					<ul class="dropdown-menu">
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/nhan-vien">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Nhân Viên</div>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link class="nav-link" to="/admin/khach-hang">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Khách Hàng</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/nha-cung-cap">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Nhà Cung Cấp</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/chuc-vu">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Chức Vụ</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/chuc-nang">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Chức Năng</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/phan-quyen">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Phân Quyền</div>
							</router-link>
						</li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">Quản lý bàn</div>
					</a>
					<ul class="dropdown-menu">
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/ban">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Bàn</div>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link class="nav-link" to="/admin/khu-vuc-ban">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Khu Vực Bàn</div>
							</router-link>
						</li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class="fa-brands fa-product-hunt"></i>
						</div>
						<div class="menu-title">Quản lý sản phẩm</div>
					</a>
					<ul class="dropdown-menu">
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/san-pham">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Sản Phẩm</div>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link class="nav-link" to="/admin/cong-thuc-mon">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Công Thức Món</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/danh-muc">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Danh Mục</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/nguyen-lieu">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Nguyên Liệu</div>
							</router-link>
						</li>
					</ul>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class="fa-solid fa-table"></i>
						</div>
						<div class="menu-title">Quản lý ca làm</div>
					</a>
					<ul class="dropdown-menu">

						<li class="nav-item">
							<router-link class="nav-link" to="/admin/ca-lam-viec">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Ca Làm Việc</div>
							</router-link>
						</li>

						<li class="nav-item">
							<router-link class="nav-link" to="/admin/phan-cong-ca-lam">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Phân Công Ca Làm</div>
							</router-link>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<router-link class="nav-link" to="/admin/hoa-don">
						<div class="parent-icon"><i class="fa-solid fa-file-invoice"></i></div>
						<div class="menu-title text-nowrap">Hóa Đơn</div>
					</router-link>
				</li>
				<li class="nav-item">
					<router-link class="nav-link" to="/admin/voucher">
						<div class="parent-icon"><i class="fa-solid fa-ticket"></i></div>
						<div class="menu-title text-nowrap">Voucher</div>
					</router-link>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class="fa-solid fa-cash-register"></i>
						</div>
						<div class="menu-title">Bán Hàng</div>
					</a>
					<ul class="dropdown-menu">
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/ban-hang">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Thu Ngân</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/bep-che-bien">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Bếp chế biến</div>
							</router-link>
						</li>

					</ul>
				</li>
				<li class="nav-item dropdown">
					<a href="javascript:;" class="nav-link dropdown-toggle dropdown-toggle-nocaret"
						data-bs-toggle="dropdown">
						<div class="parent-icon"><i class="fa-solid fa-chart-line"></i>
						</div>
						<div class="menu-title">Thống Kê</div>
					</a>
					<ul class="dropdown-menu">
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/thong-ke/doanh-thu-theo-ban">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Doanh Thu Theo Bàn</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/thong-ke/san-pham-da-ban">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Sản Phẩm Đã Bán</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/thong-ke/doanh-thu-theo-danh-muc">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Doanh Thu Theo Danh Mục</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/thong-ke/doanh-thu-theo-khu-vuc">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Doanh Thu Theo Khu Vực</div>
							</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="/admin/thong-ke/doanh-thu-theo-ngay">
								<div class="parent-icon"><i class="fa-solid fa-arrow-right"></i></div>
								<div class="menu-title text-nowrap">Doanh Thu Theo Ngày</div>
							</router-link>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</template>
<script>
export default {

}
</script>
<style></style>