
# SaherConnect

SaherConnect is a social networking and community platform built using Laravel. This project is designed to offer a dynamic, interactive experience with features like user authentication, posts, comments, following, direct messaging, real-time notifications, and content moderation.

## Table of Contents

- [SaherConnect](#saherconnect)
  - [Table of Contents](#table-of-contents)
  - [Features](#features)
  - [Installation](#installation)
    - [Prerequisites](#prerequisites)
    - [Steps to Install](#steps-to-install)
  - [Project Structure](#project-structure)
  - [Usage](#usage)
  - [Contributing](#contributing)
  - [License](#license)
  - [Contact](#contact)

## Features

- **User Authentication & Authorization:**  
  Secure registration, login, password reset, and email verification powered by Laravel Breeze.
- **Posts & Comments:**  
  Users can create posts, add comments, and reply to comments.
- **Followers System:**  
  Follow/unfollow users to manage your network and personalize your feed.
- **Direct Messaging:**  
  Real-time direct messaging with message status indicators.
- **Real-Time Notifications:**  
  Instant notifications for new comments, messages, and follower actions using Laravel Echo and WebSockets.
- **Content Moderation:**  
  Report inappropriate content and manage it through a moderation dashboard.

## Installation

### Prerequisites

- PHP 8.x (or the version specified in your `composer.json`)
- Composer
- Node.js and npm
- MySQL (or your preferred database)
- Git

### Steps to Install

1. **Clone the Repository**

   ```bash
   git clone https://github.com/QaidSaher/SaherConnect.git
   cd SaherConnect
   ```

2. **Install Composer Dependencies**

   ```bash
   composer install
   ```

3. **Install Node Dependencies**

   ```bash
   npm install
   ```

4. **Configure Environment Variables**

   Copy the example `.env` file and update the configuration as needed:

   ```bash
   cp .env.example .env
   ```

   Edit the `.env` file to configure your database and other settings.

5. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

6. **Install Laravel Breeze for Authentication**

   Breeze provides minimal authentication scaffolding (login, registration, password reset, etc.). To install:

   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   ```

   You can choose to install Breeze with Inertia if you prefer a JavaScript framework-based front-end:

   ```bash
   php artisan breeze:install inertia
   ```

7. **Compile Assets**

   After installing Breeze, compile your CSS and JavaScript assets:

   ```bash
   npm run dev
   ```

8. **Run Migrations**

   Execute the migrations to set up your database tables:

   ```bash
   php artisan migrate
   ```

9. **Serve the Application**

   Start the development server:

   ```bash
   php artisan serve
   ```

   Then visit [http://localhost:8000](http://localhost:8000) in your browser to see SaherConnect in action.

## Project Structure

Below is a simplified overview of the project structure:

```
SaherConnect
├── app
│   └── Models
│       ├── User.php
│       ├── Post.php
│       ├── Comment.php
│       ├── Follower.php
│       ├── Message.php
│       ├── Notification.php
│       └── Report.php
├── database
│   └── migrations
├── public
│   └── (assets, index.php, etc.)
├── resources
│   ├── views
│   └── (assets, layouts, etc.)
├── routes
│   ├── web.php
│   └── api.php
├── package.json
├── composer.json
└── README.md
```

## Usage

Once the application is up and running, users can:
- **Register/Login:** Access secure authentication pages.
- **Create and Interact with Posts:** Share updates, comment on posts, and engage with the community.
- **Manage Follows:** Follow and unfollow other users to curate your feed.
- **Send Direct Messages:** Communicate privately with real-time message notifications.
- **Receive Updates:** Get instant notifications on your actions through real-time features.
- **Report Content:** Flag inappropriate content for review.

## Contributing

Contributions are welcome! Follow these steps to contribute:
1. Fork the repository on GitHub.
2. Create a new branch for your feature or bugfix.
3. Commit your changes with clear commit messages.
4. Push to your fork and open a pull request.

For detailed contribution guidelines, please see [CONTRIBUTING.md](CONTRIBUTING.md).

## License

This project is open-source and available under the [MIT License](LICENSE).

## Contact

For any questions or issues, please open an issue on GitHub or contact the repository owner:

- **GitHub:** [QaidSaher](https://github.com/QaidSaher)
- **Email:** (Add your email if you wish to provide direct contact)

---

Happy coding and welcome to SaherConnect!
