#  Minimal Blog Platform# Minimal Blog (PHP + MySQL + Vanilla JS)



A modern, lightweight blog platform built with PHP, MySQL, and vanilla JavaScript. Perfect for sharing stories and managing personal blog content.This is a minimal blog scaffold using PHP (PDO) for the backend and a static frontend (HTML/CSS/JS).



##  FeaturesFolders:

- `backend/` — PHP API and SQL init file

- **User Authentication**: Secure registration and login system with email verification- `frontend/` — static UI

- **Blog Management**: Create, read, update, and delete blog posts

- **Responsive Design**: Beautiful, mobile-friendly interfaceQuick start

- **Search Functionality**: Find blogs by title, content, or author1. Create the database and table. From a MySQL client run:

- **Writer Dashboard**: Dedicated dashboard for managing your blog posts

- **Professional UI**: Modern gradient design with smooth animations   - Open `backend/init.sql` and run its contents (or run: `mysql -u root -p < backend/init.sql`).

- **Ownership Enforcement**: Users can only edit/delete their own posts

2. Configure DB creds (if needed):

##  Tech Stack

   - Edit `backend/db.php` and set `$user`, `$pass`, `$host` to match your MySQL.

- **Frontend**: HTML5, CSS3, Vanilla JavaScript

- **Backend**: PHP 7.4+3. Start the PHP built-in server (serves the backend):

- **Database**: MySQL

- **Server**: XAMPP (Apache + PHP + MySQL)   - In a terminal run (from project root):



##  Project Structure     ```

     php -S localhost:8000 -t backend

```     ```

Assignment/

├── frontend/   - The API endpoint will be: `http://localhost:8000/posts.php`

│   ├── index.html           # Home page with blog list

│   ├── blog.html            # Single blog post view4. Open the frontend:

│   ├── dashboard.html       # Writer's dashboard

│   ├── register.html        # User registration   - Open `frontend/index.html` in your browser (you can open the file directly). For CORS-friendly local testing it's easier to run a small static server or use Live Server extension in VS Code.

│   ├── login.html           # User login

│   ├── style.css            # Base stylesNotes and next steps

│   ├── extra-styles.css     # Page-specific styles- This is intentionally minimal. Recommended improvements:

│   ├── home.js              # Home page functionality  - Add input validation and sanitization server-side.

│   ├── blog-view.js         # Blog view functionality  - Add user authentication for protected actions.

│   ├── dashboard.js         # Dashboard functionality  - Use prepared statements (already used) and more robust error handling.

│   └── script.js            # Additional utilities  - Move DB credentials to a separate config file out of source control.

├── backend/

│   ├── posts.php            # Blog post API endpointsHow the pieces map

│   ├── auth.php             # Authentication endpoints- `backend/db.php` — PDO connection and CORS/JSON headers

│   └── dp.php               # Database connection- `backend/posts.php` — REST-like endpoint supporting GET, POST, PUT, DELETE

├── init.sql                 # Database schema- `backend/init.sql` — creates `blog` database and `posts` table

└── README.md               # This file- `frontend/` — UI to create/list/edit/delete posts

```

##  Installation & Setup

### Prerequisites
- XAMPP installed and running
- MySQL service active
- PHP 7.4 or higher

### Step 1: Setup Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `blog`
3. Import `init.sql` to create tables:
   - `user` table (stores user accounts)
   - `blogPost` table (stores blog posts)

### Step 2: Configure Backend

The backend uses PDO to connect to MySQL:
- **Host**: 127.0.0.1
- **User**: root
- **Password**: (empty)
- **Database**: blog

### Step 3: Run the Application

1. Start XAMPP (Apache + MySQL)
2. Navigate to: `http://localhost/Assignment/frontend/index.html`
3. Enjoy blogging! 🎉

##  User Guide

### Registration
1. Click " Register" button
2. Enter username, email, and password
3. Submit to create account

### Login
1. Click " Sign In" button
2. Enter username or email and password
3. Access your dashboard to create blogs

### Creating a Blog Post
1. Click "My Dashboard" (visible when logged in)
2. Enter blog title and content
3. Click "Post Blog" to publish

### Viewing Blogs
1. Browse all blogs on the home page
2. Use search bar to find specific blogs
3. Click "Read More →" to view full post
4. See author info and related posts

### Managing Your Blogs
1. Go to "My Dashboard"
2. View all your published posts in the table
3. Click "Edit" to modify a post
4. Click "Delete" to remove a post

### Logout
1. Click logout button (light red button in navbar)
2. Session will end and you'll return to home page

##  Database Schema

### User Table
```sql
user (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```

### Blog Post Table
```sql
blogPost (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(id)
)
```

##  Design Features

- **Color Scheme**: Professional indigo (#6366f1) and pink (#ec4899)
- **Typography**: Inter font family for clean, modern look
- **Animations**: Smooth transitions and hover effects
- **Responsive**: Works on desktop, tablet, and mobile devices
- **Accessibility**: Semantic HTML and proper contrast ratios

## 🔗 API Endpoints

### Authentication (`backend/auth.php`)

- **Register**: `POST /backend/auth.php?action=register`
  - Body: `{username, email, password}`
  
- **Login**: `POST /backend/auth.php?action=login`
  - Body: `{username/email, password}`
  
- **Logout**: `POST /backend/auth.php?action=logout`
  
- **Get Current User**: `GET /backend/auth.php?action=me`

### Blog Posts (`backend/posts.php`)

- **Get All Posts**: `GET /backend/posts.php`
  
- **Get Single Post**: `GET /backend/posts.php?id={postId}`
  
- **Create Post**: `POST /backend/posts.php`
  - Body: `{title, content}`
  - Requires: Authenticated user
  
- **Update Post**: `PUT /backend/posts.php`
  - Body: `{id, title, content}`
  - Requires: Post ownership
  
- **Delete Post**: `DELETE /backend/posts.php?id={postId}`
  - Requires: Post ownership

##  Security Features

- **Password Hashing**: Uses PHP's `password_hash()` for secure storage
- **Session Management**: Server-side sessions with credentials: 'same-origin'
- **Ownership Verification**: Users can only modify their own posts
- **SQL Injection Protection**: Uses prepared statements via PDO
- **XSS Prevention**: HTML escaping for all user content

##  Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

##  Notes

- Blog titles are limited to 255 characters
- Content can be unlimited length (LONGTEXT)
- Usernames and emails must be unique
- All timestamps are in server timezone
- Session timeout: Default PHP session timeout

## 👨 Developer

**MS Nihaaj Ahamed**
-  Email: nihaajahamed@gmail.com
-  Phone: 0759896910
-  Portfolio: https://nihaaj-ahamed.github.io/Nihaaj-portfolio/
-  LinkedIn: https://lk.linkedin.com/in/nihaaj-ahamed-911177306

##  License

This project is open source and available for educational purposes.

##  Acknowledgments

Built with modern web technologies and best practices for a clean, maintainable codebase.

---

**Happy Blogging!** 

For issues or feature requests, please contact the developer.
