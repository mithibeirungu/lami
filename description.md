# **Lami — Platform Description**

**Lami** is a web-based platform designed to simplify the consumption of automotive knowledge for beginners and enthusiasts. It serves as a centralized, high-quality source of car-related articles, curated insights, and vehicle knowledge pieces written by vetted contributors. The system is structured to support three levels of users — **Admins**, **Writers**, and **Readers** — each with clearly defined permissions that maintain content quality, editorial control, and community engagement.

---

## **1. Purpose of the Platform**

Lami exists to make car information accessible, simple, and engaging.

Many people struggle to understand automotive concepts such as vehicle specifications, features, classifications, and maintenance tips. Lami solves this by providing:

* Well-written articles explaining vehicles, concepts, comparisons, and automotive news
* Rich media content including images and categorized photo sets
* Community interactions through comments and favorites
* Easy discovery of content through tags, categories, search, and article cards

The ultimate goal is to build a beginner-friendly automotive knowledge hub.

---

## **2. Core Features**

### **2.1 Articles**

Articles are the heart of the platform. Each article includes:

* Title, excerpt, body text (rich content)
* Featured thumbnail image
* Associated tags (e.g., “SUV Basics”, “Budget Cars”, “Engine Tips”)
* One-to-many images (categorized: interior, exterior, engine, etc.)
* Publishing status (draft or published)

Articles display on the website as **cards**, each showing the thumbnail, title, and excerpt. When clicked, the user is taken to the full article page.

---

### **2.2 User Roles and Permissions**

Lami has three user types, each with a specific role in the ecosystem:

---

### **1) Admins — System Overseers**

Admins have full system authority. They can:

* Manage all articles (edit, publish, unpublish, delete)
* Manage all users (assign roles, deactivate accounts)
* Override writer content
* Manage tags and categories
* Moderate comments
* Access internal dashboards and analytics (optional future extension)

Admins act as the final gatekeepers ensuring content accuracy and system stability.

---

### **2) Writers — Content Creators**

Writers are responsible for generating the platform’s content. They can:

* Create new articles
* Edit and update their own articles
* Upload and manage article images
* Submit articles for publication
* View engagement metrics on their articles

Writers cannot publish content unless permitted; the admin has overriding authority.

---

### **3) Readers — Normal Users**

Readers form the main audience. They can:

* Read published articles
* Search and filter through content
* Leave comments on articles
* Favorite articles to save them
* Create a personal account to personalize their experience

They cannot edit or create content.

---

## **3. Interaction Features**

### **3.1 Comments**

Users can comment on articles to share thoughts or ask questions.

* Each comment is tied to a user and an article
* Admins can remove inappropriate comments
* Authors can delete comments on their articles

Commenting enhances engagement and builds community.

---

### **3.2 Favorites**

Readers can “favorite” any article they like.

* Favoriting saves the article in their personal profile
* A reader may favorite or unfavorite an article anytime
* Favoriting helps users build their own collection of useful content

This promotes deeper community interaction and increases return visits.

---

### **3.3 Tags & Categories**

Lami organizes articles via tags to simplify discovery.

Examples include:

* Car Types (SUV, Sedan, Hatchback)
* Principles (Engine Basics, Fuel Efficiency)
* Guides (Buying Tips, Maintenance Tips)
* Vehicle Features (Safety, Performance)

Tags allow flexible content grouping and filtering without rigid structures.

---

## **4. Media Management**

Lami supports extensive image handling for each article. Every image can be assigned a **category**:

* Exterior
* Interior
* Engine
* Front View
* Rear View
* Side Profile
* Dashboard
* Wheels
* Other

This helps writers organize content and gives readers a richer, visual learning experience.

---

## **5. Technology Stack (MVP)**

Lami’s minimal viable product uses:

* **Laravel 12 (PHP)** as the backend framework
* **PostgreSQL** as the database
* **Vue 3 + Vite** as the front-end framework
* **Laravel Sanctum** for SPA authentication
* **Local storage (public disk)** for image uploads

The system is fully API-driven, enabling scalability and later mobile expansion.

---

## **6. System Goals**

Lami aims to:

1. Provide high-quality, beginner-friendly automotive education
2. Maintain clear editorial structure with verified writers
3. Build a safe, moderated community of readers
4. Establish a scalable content platform ready for future enhancements:

   * Vehicle comparisons
   * Car database
   * User profiles
   * Saved reading lists
   * Mobile app version

---

## **7. Summary for Developers and New Team Members**

**Lami is a structured automotive knowledge platform that blends article publishing, user interaction, and media-rich educational content.**
Admin controls maintain quality, writers enrich the library with new articles, and users engage through comments and favorites. The technology stack ensures fast delivery using Laravel APIs and a Vue-based single-page application.

The system is simple enough for MVP release, yet structured enough to grow into a full automotive information ecosystem.


