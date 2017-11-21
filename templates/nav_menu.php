<nav id="menu">
	<ul>
		<li class="Label"><?php include("loginstatus.php"); ?></li>
		<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/basic/search.php" tabindex="-1">بحث</a></li>
		<li class="admin edarh t3lem"><span>بيانات أساسية</span>
			<ul>
				<li class="admin t3lem"><a href="<?php echo ROOT;?>/basic/edarah_add.php" tabindex="-1"><?php echo get_gender_label('es', 'ال'); ?> والمستخدمون</a></li>
				<li class="admin edarh t3lem"><a href="<?php echo ROOT;?>/basic/halakah_add.php" tabindex="-1">الحلقات</a></li>
				<li class="admin edarh t3lem"><a href="<?php echo ROOT;?>/basic/teacher_add.php" tabindex="-1"><?php echo get_gender_label('ts', 'ال'); ?></a></li>
				<li class="admin edarh t3lem"><a href="<?php echo ROOT;?>/basic/student_add.php" tabindex="-1"><?php echo get_gender_label('sts', 'ال'); ?></a></li>
				<li class="admin edarh t3lem"><a href="<?php echo ROOT;?>/basic/emp_add.php" tabindex="-1"><?php echo get_gender_label('emps', ''); ?></a></li>
				<li class="edarh"><a href="<?php echo ROOT;?>/basic/transfer_st_search.php" tabindex="-1">طلب نقل <?php echo get_gender_label('st'); ?></a></li>
				<li class="admin"><a href="<?php echo ROOT;?>/basic/transfer_st_approve.php" tabindex="-1">اعتماد طلبات نقل <?php echo get_gender_label('sts', 'ال'); ?></a></li>
			</ul>
		</li>
		<li class="admin edarh er mktbr alaqat"><span>الارتقاء</span>
			<ul>
				<li class="admin edarh"><a href="<?php echo ROOT;?>/ertiqa/viewexams.php" tabindex="-1">الحجوزات السابقة</a></li>
				<li class="admin er mktbr"><a href="<?php echo ROOT;?>/ertiqa/addexamdate.php" tabindex="-1">الحجز والشهادة</a></li>
				<!-- <li class="admin er"><a href="<?php echo ROOT;?>/ertiqa/mkhtbr_add.php" tabindex="-1">المعلمون المختبرون</a></li>-->
				<li class="admin edarh er alaqat"><a href="<?php echo ROOT;?>/ertiqa/payments.php" tabindex="-1">الكشوفات المالية</a></li>
				<li class="admin edarh er alaqat"><a href="<?php echo ROOT;?>/ertiqa/no_ertiqa.php" tabindex="-1">كشف بمن لم يختبر</a></li>
				<li class="admin er alaqat"><a href="<?php echo ROOT;?>/ertiqa/exams_filter.php" tabindex="-1">تقرير مخصص</a></li>
			</ul>
		</li>

		<li class="admin edarh ms alaqat"><span>مسابقات</span>
			<ul>
				<li class="edarh"><a href="<?php echo ROOT;?>/fahd/featured_edarah_add.php" tabindex="-1">التسجيل في الإدارة المتميزة</a></li>
				<li class="admin ms t3lem edarh alaqat"><a href="<?php echo ROOT;?>/fahd/featured_edarah_view_all.php" tabindex="-1">المسجلون في الإدارة المتميزة</a></li>

				<li class="admin edarh ms alaqat"><a href="<?php echo ROOT;?>/fahd/view_registered.php" tabindex="-1">المسجلون في الفهد</a></li>
				<li class="admin ms "><a href="<?php echo ROOT;?>/fahd/view_registered_2_access.php" tabindex="-1">المسجلون في الفهد (ACCESS)</a></li>

				<li class="admin edarh ms alaqat"><a href="<?php echo ROOT;?>/mutqin/view_registered.php" tabindex="-1">المسجلون برنامج متقن</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/mutqin/view_registered_2_access.php" tabindex="-1">المسجلون برنامج متقن (ACCESS)</a></li>

                <li class="admin edarh ms alaqat"><a href="<?php echo ROOT;?>/etqan/view_registered.php" tabindex="-1">المسجلون في أمير الرياض</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/etqan/view_registered_2_access.php" tabindex="-1">المسجلون في أمير الرياض (ACCESS)</a></li>

				<li class="admin edarh ms alaqat"><a href="<?php echo ROOT;?>/shabab/view_registered.php" tabindex="-1">المسجلون في الهيئة العامة للرياضة</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/shabab/view_registered_2_access.php" tabindex="-1">المسجلون في الهيئة العامة للرياضة (ACCESS)</a></li>

				<li class="admin edarh ms alaqat"><a href="<?php echo ROOT;?>/salman/view_registered.php" tabindex="-1">المسجلون في الملك سلمان</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/salman/view_registered_2_access.php" tabindex="-1">المسجلون في الملك سلمان (ACCESS)</a></li>

				<li class="admin ms t3lem edarh"><a href="<?php echo ROOT;?>/fahd/featured_teacher_view_all.php" tabindex="-1">المسجلون في المعلم المتميز</a></li>
				<li class="admin ms t3lem"><a href="<?php echo ROOT;?>/fahd/featured_teacher_view_all2access.php" tabindex="-1">المسجلون في المعلم المتميز(جميع البنود)</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/fahd/featured_teacher_recalculate.php" tabindex="-1">إعادة احتساب درجات (المعلم المتميز)</a></li>
				<li class="admin ms"><a href="<?php echo ROOT;?>/fahd/featured_edarah_recalculate.php" tabindex="-1">إعادة احتساب درجات (الإدارة المتميزة)</a></li>
			</ul>
		</li>
		<li class="<?php echo $all_groups_space; ?>"><span>احصائيات</span>
			<ul>
				<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/ertiqa/statistics/count_exams_per_edarah.php" tabindex="-1">(الارتقاء) الغياب والجودة حسب <?php echo get_gender_label('e', 'ال'); ?></a></li>
				<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/ertiqa/statistics/edarah_points.php" tabindex="-1">(الارتقاء) نقاط <?php echo get_gender_label('e', 'ال'); ?></a></li>
				<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/ertiqa/statistics/teachers_points.php" tabindex="-1">(الارتقاء) نقاط <?php echo get_gender_label('ts', 'ال'); ?></a></li>
				<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/ertiqa/statistics/teachers_points_2.php" tabindex="-1">(الارتقاء) نقاط <?php echo get_gender_label('ts', 'ال'); ?> -
						حسب <?php echo get_gender_label('e', 'ال'); ?></a></li>
				<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/basic/statistics/st_levels.php" tabindex="-1">احصائية بالمراحل الدراسية للطلاب والطالبات </a></li>
			</ul>
		</li>
		<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/basic/kashf/kashf_form.php" tabindex="-1">كشوفات</a></li>
		<li class="<?php echo $all_groups_space; ?>"><a href="<?php echo ROOT;?>/basic/statistics/school_staff.php" tabindex="-1">استمارة بيانات <?php echo get_gender_label('e', ''); ?></a></li>
		<li class="admin edarh t3lem"><a href="<?php echo ROOT;?>/basic/statistics/student_exams.php" tabindex="-1"> كشف مرتقيات كل  <?php echo get_gender_label('st', ''); ?></a></li>
		<li><span>صفحة <?php echo get_gender_label('t', 'ال'); ?> و<?php echo get_gender_label('st', 'ال'); ?></span>
			<ul>
				<li><a href="<?php echo ROOT;?>/ertiqa/statistics/teacherexams.php" tabindex="-1">صفحة <?php echo get_gender_label('t', 'ال'); ?></a></li>
				<li><a href="<?php echo ROOT;?>/ertiqa/statistics/studentexams.php" tabindex="-1">صفحة <?php echo get_gender_label('st', 'ال'); ?></a></li>
			</ul>
		</li>
		<li>

		</li>
		<li><a href="#menu">إغلاق</a></li>
	</ul>
</nav>
