<?php

class AllCourses {
	public function __construct(){

	}
	public function GetCategories(){
		global $wp;
        // Replace with your desired category IDs
		$included_category_ids = array(46, 47, 49, 164, 169, 170, 171);
		$course_categories = get_terms(array(
			'taxonomy'   => 'course-cat',
			'hide_empty' => true,
			'include'    => $included_category_ids,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 24
		));
		if (!empty($course_categories) && !is_wp_error($course_categories)) {
		    return $course_categories;
		} else {
			return false;
		}
	}
}