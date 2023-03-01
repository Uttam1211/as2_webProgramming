<?php

namespace josJobs;


class Routes implements \CSY2028\Routes
{
    private $pdo;

    private $applicationsTable;
    private $categoriesTable;
    private $enquiriesTable;
    private $jobsTable;
    private $locationsTable;
    private $usersTable;

    private $authentication;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->applicationsTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'applications',
            'application_id'
        );
        $this->categoriesTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'categories',
            'category_id',
            '\josJobs\Entity\Category',
            [
                &$this->jobsTable
            ]
            );
        $this->enquiriesTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'enquiries',
            'enquiry_id',
            '\josJobs\Entity\Enquiry',
            [
                &$this->usersTable
            ]
        );
        $this->jobsTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'jobs',
            'job_id',
            '\josJobs\Entity\Job',
            [
                &$this->applicationsTable,
                &$this->categoriesTable,
                &$this->locationsTable,
                &$this->usersTable
            ]
        );
        $this->locationsTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'locations',
            'location_id',
            '\josJobs\Entity\Location',
            [
                &$this->jobsTable
            ]
        );
        $this->usersTable = new \CSY2028\DatabaseTable(
            $this->pdo,
            'users',
            'user_id',
            '\josJobs\Entity\User',
            [
                &$this->jobsTable
            ]
        );

        $this->authentication = new \CSY2028\Authentication($this->usersTable, 'email', 'password');
    }

    public function getRoutes(): array
    {
        $authController = new \josJobs\Controllers\Auth($this->authentication, $_GET, $_POST);
        $categoryController = new \josJobs\Controllers\Category(
            $this->authentication,
            $this->categoriesTable,
            $this->jobsTable,
            $_GET,
            $_POST
        );
        $enquiryController = new \josJobs\Controllers\Enquiry(
            $this->authentication,
            $this->enquiriesTable,
            $this->usersTable,
            $_GET,
            $_POST
        );
        $jobController = new \josJobs\Controllers\Job(
            $this->authentication,
            $this->applicationsTable,
            $this->categoriesTable,
            $this->jobsTable,
            $this->locationsTable,
            $this->usersTable,
            $_GET,
            $_POST,
            new \CSY2028\FileUpload(
                $_FILES,
                'cv',
                [
                    'uploadsDir' => '/uploads/cvs/',
                    'namePrefix' => 'cv_',
                    'validFileExts' => ['docx', 'doc', 'pdf', 'rtf'],
                    'maxFileSizeMB' => 0.5
                ]
            )
        );
        $locationController = new \josJobs\Controllers\Location(
            $this->authentication,
            $this->locationsTable,
            $this->jobsTable,
            $_GET,
            $_POST
        );
        $rootController = new \josJobs\Controllers\Root(
            $this->authentication,
            $this->categoriesTable,
            $this->jobsTable,
  
        );
        $userController = new \josJobs\Controllers\User(
            $this->authentication,
            $this->jobsTable,
            $this->usersTable,
            $_GET,
            $_POST
        );

        $routes = [
            '' => [
                'GET' => [
                    'controller' => $rootController,
                    'action' => 'home'
                ]
            ],
            'about' => [
                'GET' => [
                    'controller' => $rootController,
                    'action' => 'about'
                ]
            ],
            'about/faq' => [
                'GET' => [
                    'controller' => $rootController,
                    'action' => 'faq'
                ]
            ],
            'contact' => [
                'GET' => [
                    'controller' => $rootController,
                    'action' => 'contact'
                ],
                'POST' => [
                    'controller' => $enquiryController,
                    'action' => 'create'
                ]
            ],
            'jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'read'
                ]
            ],
            'jobs/apply' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'apply'
                ],
                'POST' => [
                    'controller' => $jobController,
                    'action' => 'saveApply'
                ]
            ],
            'admin' => [
                'GET' => [
                    'controller' => $rootController,
                    'action' => 'dashboard'
                ],
                'login' => true
            ],
            'admin/categories' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'read'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_READ_CATEGORIES
            ],
            'admin/categories/create' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_CREATE_CATEGORIES
            ],
            'admin/categories/update' => [
                'GET' => [
                    'controller' => $categoryController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_UPDATE_CATEGORIES
            ],
            'admin/categories/delete' => [
                'POST' => [
                    'controller' => $categoryController,
                    'action' => 'delete'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_DELETE_CATEGORIES
            ],
            'admin/enquiries' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'action' => 'read'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_READ_ENQUIRIES
            ],
            'admin/enquiries/enquiry' => [
                'GET' => [
                    'controller' => $enquiryController,
                    'action' => 'readOne'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_READ_ENQUIRIES
            ],
            'admin/enquiries/assign' => [
                'POST' => [
                    'controller' => $enquiryController,
                    'action' => 'assign'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_ASSIGN_ENQUIRIES
            ],
            'admin/enquiries/complete' => [
                'POST' => [
                    'controller' => $enquiryController,
                    'action' => 'complete'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_COMPLETE_ENQUIRIES
            ],
            'admin/enquiries/delete' => [
                'POST' => [
                    'controller' => $enquiryController,
                    'action' => 'delete'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_DELETE_ENQUIRIES
            ],
            'admin/jobs' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'readPrivileged'
                ],
                'login' => true
            ],
            'admin/jobs/applications' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'applications'
                ],
                'login' => true
            ],
            'admin/jobs/create' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $jobController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_CREATE_JOBS
            ],
            'admin/jobs/update' => [
                'GET' => [
                    'controller' => $jobController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $jobController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                // 'permissions' => \josJobs\Entity\User::PERM_UPDATE_ANY_JOBS // TODO: Determine what to-do with this
            ],
            'admin/jobs/archive' => [
                'POST' => [
                    'controller' => $jobController,
                    'action' => 'archive'
                ],
                'login' => true,
                // 'permissions' => \josJobs\Entity\User::PERM_ARCHIVE_ANY_JOBS // TODO: Determine what to-do with this
            ],
            'admin/jobs/delete' => [
                'POST' => [
                    'controller' => $jobController,
                    'action' => 'delete'
                ],
                'login' => true,
                // 'permissions' => \josJobs\Entity\User::PERM_DELETE_ANY_JOBS // TODO: Determine what to-do with this
            ],
            'admin/locations' => [
                'GET' => [
                    'controller' => $locationController,
                    'action' => 'read'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_READ_LOCATIONS
            ],
            'admin/locations/create' => [
                'GET' => [
                    'controller' => $locationController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $locationController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_CREATE_LOCATIONS
            ],
            'admin/locations/update' => [
                'GET' => [
                    'controller' => $locationController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $locationController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_UPDATE_LOCATIONS
            ],
            'admin/locations/delete' => [
                'POST' => [
                    'controller' => $locationController,
                    'action' => 'delete'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_DELETE_LOCATIONS
            ],
            'admin/users' => [
                'GET' => [
                    'controller' => $userController,
                    'action' => 'read'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_READ_USERS
            ],
            'admin/users/create' => [
                'GET' => [
                    'controller' => $userController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $userController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_CREATE_USERS
            ],
            'admin/users/update' => [
                'GET' => [
                    'controller' => $userController,
                    'action' => 'update'
                ],
                'POST' => [
                    'controller' => $userController,
                    'action' => 'saveUpdate'
                ],
                'login' => true,
                // 'permissions' => \josJobs\Entity\User::PERM_UPDATE_USERS // TODO: Determine what to-do with this
            ],
            'admin/users/delete' => [
                'POST' => [
                    'controller' => $userController,
                    'action' => 'delete'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_DELETE_USERS
            ],
            'admin/users/permissions' => [
                'GET' => [
                    'controller' => $userController,
                    'action' => 'permissions'
                ],
                'POST' => [
                    'controller' => $userController,
                    'action' => 'savePermissions'
                ],
                'login' => true,
                'permissions' => \josJobs\Entity\User::PERM_PERMISSIONS_USERS
            ],
            'id/login' => [
                'GET' => [
                    'controller' => $authController,
                    'action' => 'loginForm'
                ],
                'POST' => [
                    'controller' => $authController,
                    'action' => 'processLogin'
                ]
            ],
            'id/logout' => [
                'GET' => [
                    'controller' => $authController,
                    'action' => 'logout'
                ]
            ],
            '403' => [
                'GET' => [
                    'controller' => $authController,
                    'action' => 'forbidden'
                ]
            ]
        ];

        return $routes;
    }

    public function getLayoutVars($title, $output): array
    {
        $categories = $this->categoriesTable->findAll();
        $user = $this->getAuthentication()->getUser();

        return [
            'title' => $title,
            'output' => $output,
            'categories' => $categories,
            'user' => $user
        ];
    }

    public function getAuthentication(): \CSY2028\Authentication
    {
        return $this->authentication;
    }

    public function checkPermission($permission): bool
    {
        $user = $this->authentication->getUser();

        if ($user && $user->hasPermission($permission)) {
            return true;
        } else {
            return false;
        }
    }
}
