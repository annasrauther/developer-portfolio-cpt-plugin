# Developer Portfolio CPT

Effortlessly Manage Your Portfolio Website Content with WordPress!

## Description

The **Developer Portfolio CPT** is a user-friendly custom post type plugin designed for individuals and businesses who want to use WordPress to manage content for their portfolio websites. This plugin seamlessly registers custom post types (CPTs) to help you showcase your skills, experiences, and portfolios, making it easy to create an impressive portfolio website.

## Plugin Information

- Plugin Name: Developer Portfolio CPT
- Description: Effortlessly Manage Your Portfolio Website Content with WordPress! Seamlessly registers custom post types (CPTs) for displaying skills, experiences, and portfolios on your website.
- Author: Al Annas Rauther
- Author URI: [annasrauther.com](https://annasrauther.com)
- Version: 1.0
- License: GPL2

## Installation

1. Download the plugin as a ZIP file from the [GitHub repository](https://github.com/annasrauther/developer-portfolio-cpt-plugin).
2. In your WordPress dashboard, go to **Plugins** > **Add New** and click on the **Upload Plugin** button.
3. Choose the ZIP file you downloaded and click **Install Now**.
4. Once installed, activate the plugin.

## Key Features

- **Manage Experiences with Ease:** Create a compelling list of work experiences with the following fields:

  - **Title:** The title of the experience.
  - **Company Name:** The name of the company where the experience took place.
  - **Company Logo:** The logo of the company. *(Upload an image)*
  - **Company URL:** The URL of the company's website.
  - **Duration:** The duration of the experience. *(E.g., "January 2022 - March 2023")*
  - **Description:** A brief description of the experience.

  **REST API URL:** `/wp-json/wp/v2/experience`
  ```json
    {
        "id": 1,
        "title": {
            "rendered": "Job Title"
        },
        "payload": {
            "company_name": "ABC Company",
            "company_location": "City, State",
            "company_logo": "https://example.com/wp-content/uploads/company_logo.png",
            "company_url": "https://www.abc-company.com",
            "duration": "January 2022 - March 2023",
            "description": "<p>I worked as a developer at ABC Company...</p>"
        }
    }
  ```

- **Effortlessly Showcase Portfolios:** Display your remarkable portfolios with the following fields:

  - **Title:** The title of the portfolio.
  - **Client:** The name of the client associated with the portfolio.
  - **URL:** The URL of the portfolio project.
  - **Description:** A detailed description of the portfolio project.
  - **Skills:** The skills utilized in the portfolio project. *(Multiple selections allowed)*

  **REST API URL:** `/wp-json/wp/v2/portfolio`
  ```json
    {
        "id": 1,
        "title": {
            "rendered": "Project Name"
        },
        "payload": {
        "client": "XYZ Client",
        "featured_image": "https://example.com/wp-content/uploads/portfolio_project_featured_image.jpg",
        "url": "https://www.example-portfolio-project.com",
        "description": "<p>This project was about...</p>",
        "skills": ["Programming", "Design"]
        }
    }
  ```

- **Flaunt Your Skills:** Highlight your skills with the following fields:

  - **Title:** The name of the skill.
  - **Type:** The type or category of the skill. *(E.g., Programming, Design, Marketing)*
  - **Description:** A brief description of the skill.
  - **Featured:** A checkbox to indicate if the skill is featured.

  **REST API URL:** `/wp-json/wp/v2/skill`
  ```json
    {
        "id": 1,
        "title": {
            "rendered": "HTML5"
        },
        "payload": {
            "type": "Front End",
            "description": "Web markup language for pages.",
            "featured": true
        }
    }
  ```

- **REST API Support:** Share your accomplishments with the world! The plugin comes with built-in REST API support, allowing external users to consume your experience, portfolio, and skill data.

- **Eye-Catching Featured Images:** Make your portfolios stand out with visually appealing featured images for skills.

## Usage

### Experience

- Experiences can be managed under **Experiences** in the WordPress dashboard.
- Each Experience entry can have the following fields:

  - **Title:** *(Text)* The title of the experience.
  - **Company Name:** *(Text)* The name of the company where the experience took place.
  - **Company Location:** *(Text)* The location of the company.
  - **Company Logo:** *(Image)* The logo of the company.
  - **Company URL:** *(URL)* The URL of the company's website.
  - **Duration:** *(Text)* The duration of the experience. *(E.g., "January 2022 - March 2023")*
  - **Description:** *(Textarea/WYSIWYG)* A brief description of the experience.

- Experiences are shown in the REST API for external consumption.

### Portfolio

- Portfolios can be managed under **Portfolios** in the WordPress dashboard.
- Each Portfolio entry can have the following fields:

  - **Title:** *(Text)* The title of the portfolio.
  - **Client:** *(Select)* The name of the client associated with the portfolio.
  - **URL:** *(URL)* The URL of the portfolio project.
  - **Description:** *(Textarea/WYSIWYG)* A detailed description of the portfolio project.
  - **Skills:** *(Select)* The skills utilized in the portfolio project. *(Multiple selections allowed)*

- Portfolios are shown in the REST API for external consumption.

### Skills

- Skills can be managed under **Skills** in the WordPress dashboard.
- Each Skill entry can have the following fields:

  - **Title:** *(Text)* The name of the skill.
  - **Type:** *(Text)* The type or category of the skill. *(E.g., Programming, Design, Marketing)*
  - **Description:** *(Textarea)* A brief description of the skill.
  - **Featured:** *(Checkbox)* A checkbox to indicate if the skill is featured.

- Skills are shown in the REST API for external consumption.
- The Skills list in the dashboard displays a "Featured" column to identify featured skills.

### Custom Ordering

- All three Custom Post Types (Experience, Portfolio, and Skills) support custom ordering using the Simple Custom Post Order (SCPO) plugin.
- To set a custom order for the posts, go to the respective post type's dashboard, and use the drag-and-drop feature provided by the SCPO plugin.
- The custom order set in the dashboard will be replicated in the REST API response, allowing you to fetch posts with the same order.
- To get a list of posts in the custom order using the REST API, make a GET request to the following endpoint:
  ```
  /wp-json/wp/v2/{post_type}?orderby=menu_order&order=asc
  ```
- Replace `{post_type}` with the slug of the post type (e.g., `experience`, `portfolio`, `skill`). This will retrieve the posts ordered based on the custom order set in the dashboard using the SCPO plugin. The posts will be displayed in ascending order.
- You can also use `order=desc` to retrieve the posts in descending order.
- Note: For the custom ordering to work, make sure to have the SCPO plugin installed and activated in your WordPress installation.

## License

This plugin is licensed under the GNU General Public License - see the [LICENSE](LICENSE) file for details.
