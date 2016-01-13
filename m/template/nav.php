<?php
	require_once __DIR__.'/../../nl-config.php';

	function htmlNav() {
?>
	<!--NAV-->
	<nav>
		<section>
			<div id="category_list">
				<ul>
					<li><a href="{{selectedCategoryID<=0?'javascript:;':'/home'}}">All</a></li>
					<li ng-repeat-start="category in categoryList">-</li>
					<li ng-repeat-end>
						<a href="{{selectedCategoryID==category.categoryID?'javascript:;':'/home/'+category.categoryID}}" >
							<span ng-bind-html="category.categoryName"></span>
						</a>
					</li>
				</ul>
			</div>
		</section>
	</nav>
<?php
	}
?>