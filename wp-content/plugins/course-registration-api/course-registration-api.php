<?php
/**
 * Plugin Name: Course Registration API by Yanchuan
 * Description: Custom API endpoints for the Course Registration System
 * Version: 1.0 (beta!)
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register our API endpoints when WordPress initializes
add_action('rest_api_init', function() {
    
register_rest_route('course-api/v1', '/unregister', [
    'methods' => 'POST',
    'callback' => 'unregister_course',
    'permission_callback' => '__return_true'
]);

    // Course related endpoints
    register_rest_route('course-api/v1', '/courses', [
        'methods' => 'GET',
        'callback' => 'get_all_courses',
        'permission_callback' => '__return_true'
    ]);
    
    register_rest_route('course-api/v1', '/courses/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_single_course',
        'permission_callback' => '__return_true'
    ]);
    
    register_rest_route('course-api/v1', '/courses/search', [
        'methods' => 'GET',
        'callback' => 'search_courses',
        'permission_callback' => '__return_true'
    ]);
    
    // Shopping cart endpoints
    register_rest_route('course-api/v1', '/cart/add', [
        'methods' => 'POST',
        'callback' => 'add_to_cart',
        'permission_callback' => '__return_true'
    ]);
    
    register_rest_route('course-api/v1', '/cart/remove', [
        'methods' => 'POST',
        'callback' => 'remove_from_cart',
        'permission_callback' => '__return_true'
    ]);
    
    register_rest_route('course-api/v1', '/cart/(?P<student_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_cart',
        'permission_callback' => '__return_true'
    ]);
    
    // Registration endpoints
    register_rest_route('course-api/v1', '/register', [
        'methods' => 'POST',
        'callback' => 'register_for_course',
        'permission_callback' => '__return_true'
    ]);
    
    // Schedule endpoints
    register_rest_route('course-api/v1', '/schedule/(?P<student_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_student_schedule',
        'permission_callback' => '__return_true'
    ]);
    
    // User authentication endpoints
    register_rest_route('course-api/v1', '/login', [
        'methods' => 'POST',
        'callback' => 'user_login',
        'permission_callback' => '__return_true'
    ]);
    
    register_rest_route('course-api/v1', '/register-user', [
        'methods' => 'POST',
        'callback' => 'user_register',
        'permission_callback' => '__return_true'
    ]);
});

// Course related functions
function get_all_courses() {
    global $wpdb;
    
    // Get courses from our custom table
    $courses = $wpdb->get_results(
        "SELECT * FROM `cr_course` WHERE 1=1",
        ARRAY_A
    );
    
    // Return the results
    return rest_ensure_response($courses);
}

function get_single_course($request) {
    global $wpdb;
    
    // Get the course ID from the URL
    $id = $request['id'];
    
    // Get the course from our custom table
    $course = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM `cr_course` WHERE course_id = %d",
            $id
        ),
        ARRAY_A
    );
    
    // Return the result
    return rest_ensure_response($course);
}


function search_courses($request) {
    global $wpdb;

    $keyword = isset($request['keyword']) ? sanitize_text_field($request['keyword']) : '';
    $department = isset($request['department']) ? sanitize_text_field($request['department']) : '';

    $where_clauses = [];
    $prepare_values = [];

    if (!empty($keyword)) {
        $where_clauses[] = "(course_name LIKE %s OR course_code LIKE %s OR description LIKE %s)";
        $keyword_param = '%' . $wpdb->esc_like($keyword) . '%';
        $prepare_values[] = $keyword_param;
        $prepare_values[] = $keyword_param;
        $prepare_values[] = $keyword_param;
    }

    if (!empty($department)) {
        $where_clauses[] = "department = %s";
        $prepare_values[] = $department;
    }

    $where_sql = '';
    if (!empty($where_clauses)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
    }

    $sql = "SELECT * FROM `cr_course` $where_sql";

    if (!empty($prepare_values)) {
        $sql = $wpdb->prepare($sql, ...$prepare_values);
    }

    $results = $wpdb->get_results($sql, ARRAY_A);

    return rest_ensure_response($results);
}


// Shopping cart functions
function add_to_cart($request) {
    global $wpdb;
    
    // Get data from the request
    $student_id = $request['student_id'];
    $course_id = $request['course_id'];
    
    // Check if already in cart
    $existing = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM cr_shopping_cart 
            WHERE student_id = %d AND course_id = %d",
            $student_id,
            $course_id
        )
    );
    
    if ($existing > 0) {
        return new WP_Error(
            'already_in_cart',
            'Course is already in shopping cart',
            ['status' => 400]
        );
    }
    
    // Add to cart
    $result = $wpdb->insert(
        "cr_shopping_cart",
        [
            'student_id' => $student_id,
            'course_id' => $course_id,
            'added_date' => current_time('mysql')
        ]
    );
    
    // Check if successful
    if ($result) {
        return [
            'success' => true,
            'message' => 'Course added to cart'
        ];
    } else {
        return new WP_Error(
            'cart_add_failed',
            'Failed to add course to cart',
            ['status' => 500]
        );
    }
}

function remove_from_cart($request) {
    global $wpdb;
    
    // Get cart ID from the request
    $cart_id = $request['cart_id'];
    
    // Remove from cart
    $result = $wpdb->delete(
        "cr_shopping_cart",
        ['cart_id' => $cart_id]
    );
    
    // Check if successful
    if ($result) {
        return [
            'success' => true,
            'message' => 'Course removed from cart'
        ];
    } else {
        return new WP_Error(
            'cart_remove_failed',
            'Failed to remove course from cart',
            ['status' => 500]
        );
    }
}

function get_cart($request) {
    global $wpdb;
    
    // Get student ID from the URL
    $student_id = $request['student_id'];
    
    // Get cart items with course details
    $cart_items = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT c.*, sc.cart_id, sc.added_date 
            FROM cr_shopping_cart sc
            JOIN cr_course c ON sc.course_id = c.course_id
            WHERE sc.student_id = %d",
            $student_id
        ),
        ARRAY_A
    );
    
    // Return the results
    return rest_ensure_response($cart_items);
}

// Registration function
function register_for_course($request) {
    global $wpdb;
    
    // Get data from the request
    $student_id = $request['student_id'];
    $course_id = $request['course_id'];
    
    // Check if the student is already registered
    $existing = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM cr_registration 
             WHERE student_id = %d AND course_id = %d AND status = 'confirmed'",
            $student_id,
            $course_id
        )
    );
    
    if ($existing > 0) {
        return new WP_Error(
            'already_registered',
            'Student is already registered for this course',
            ['status' => 400]
        );
    }
    
    // Check course capacity
    $course = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT capacity, seats_filled FROM cr_course WHERE course_id = %d",
            $course_id
        )
    );
    
    if ($course->seats_filled >= $course->capacity) {
        return new WP_Error(
            'course_full',
            'Course has reached maximum capacity',
            ['status' => 400]
        );
    }
    
    // Start transaction to ensure data consistency
    $wpdb->query('START TRANSACTION');
    
    // Add the registration
    $registration_result = $wpdb->insert(
        "cr_registration",
        [
            'student_id' => $student_id,
            'course_id' => $course_id,
            'registration_date' => current_time('mysql'),
            'status' => 'confirmed'
        ]
    );
    
    // Update course seats filled
    $update_result = $wpdb->update(
        "cr_course",
        ['seats_filled' => $course->seats_filled + 1],
        ['course_id' => $course_id]
    );
    
    // Remove from shopping cart
    $wpdb->delete(
        "cr_shopping_cart",
        [
            'student_id' => $student_id,
            'course_id' => $course_id
        ]
    );
    
    // Commit or rollback based on results
    if ($registration_result && $update_result) {
        $wpdb->query('COMMIT');
        return [
            'success' => true,
            'message' => 'Registration successful'
        ];
    } else {
        $wpdb->query('ROLLBACK');
        return new WP_Error(
            'registration_failed',
            'Failed to register for the course',
            ['status' => 500]
        );
    }
}

// Schedule function
function get_student_schedule($request) {
    global $wpdb;
    
    // Get student ID from the URL
    $student_id = $request['student_id'];
    
    // Get student's schedule with course and instructor details
    $schedule = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT c.*, r.registration_id, r.registration_date, r.status, i.first_name as instructor_first_name, i.last_name as instructor_last_name
            FROM cr_registration r
            JOIN cr_course c ON r.course_id = c.course_id
            JOIN cr_instructors i ON c.instructor_id = i.instructor_id
            WHERE r.student_id = %d AND r.status = 'confirmed'",
            $student_id
        ),
        ARRAY_A
    );
    
    // Return the results
    return rest_ensure_response($schedule);
}

// User authentication functions
function user_login($request) {
    global $wpdb;
    
    // Get login credentials
    $email = $request['email'];
    $password = $request['password'];
    
    // Find student by email
    $student = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM cr_student WHERE email = %s",
            $email
        ),
        ARRAY_A
    );
    
    // Check if student exists
    if (!$student) {
        return new WP_Error(
            'invalid_credentials',
            'Email not found',
            ['status' => 401]
        );
    }
     
    // Verify password (in real app, use password_verify)
    // This assumes passwords are not hashed in the database
    if ($password !== $student['password']) {
        return new WP_Error(
            'invalid_credentials',
            'Incorrect password',
            ['status' => 401]
        );
    }
    
    // Remove password from response for security
    unset($student['password']);
    
    // Return user data
    return [
        'success' => true,
        'user' => $student
    ];
}

function user_register($request) {
    global $wpdb;

    // Get parameters properly from REST request
    $first_name = $request->get_param('first_name');
    $last_name = $request->get_param('last_name');
    $email = $request->get_param('email');
    $password = $request->get_param('password');
    $phone_number = $request->get_param('phone_number') ?? '';
    $major = $request->get_param('major') ?? '';

    if (!$first_name || !$last_name || !$email || !$password) {
        return new WP_Error('missing_fields', 'Required fields are missing.', ['status' => 400]);
    }

    // Check if email already exists
    $existing = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM cr_student WHERE email = %s", $email)
    );

    if ($existing > 0) {
        return new WP_Error('email_exists', 'Email already registered', ['status' => 400]);
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $hashed_password = $password;

    // Insert into DB
    $result = $wpdb->insert("cr_student", [
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'email'        => $email,
        'password'     => $hashed_password,
        'phone_number' => $phone_number,
        'major'        => $major
    ]);

    if ($result) {
        $student_id = $wpdb->insert_id;
        return [
            'success' => true,
            'message' => 'Registration successful',
            'user_id' => $student_id
        ];
    } else {
        return new WP_Error(
            'registration_failed',
            'Failed to register user: ' . $wpdb->last_error,
            ['status' => 500]
        );
    }
}


function unregister_course($request) {
    global $wpdb;

    $registration_id = intval($request['registration_id']);

    if (!$registration_id) {
        return new WP_Error('invalid_id', 'Invalid registration ID', ['status' => 400]);
    }

    $registration = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM cr_registration WHERE registration_id = %d", $registration_id),
        ARRAY_A
    );

    if (!$registration) {
        return new WP_Error('not_found', 'Registration not found', ['status' => 404]);
    }

    $course_id = $registration['course_id'];

    $wpdb->query('START TRANSACTION');

    $deleted = $wpdb->delete(
        "cr_registration",
        ['registration_id' => $registration_id]
    );

    $updated = $wpdb->query(
        $wpdb->prepare(
            "UPDATE cr_course SET seats_filled = seats_filled - 1 WHERE course_id = %d AND seats_filled > 0",
            $course_id
        )
    );

    if ($deleted && $updated !== false) {
        $wpdb->query('COMMIT');
        return ['success' => true, 'message' => 'Unregistered successfully'];
    } else {
        $wpdb->query('ROLLBACK');
        return new WP_Error('unregister_failed', 'Failed to unregister from course', ['status' => 500]);
    }
}


?>
