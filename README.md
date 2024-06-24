# ToDo List WordPress plugin

## Installation (with Docker)

1. Create a folder on your system for the Docker setup

2. Place "docker-compose.yaml" file inside the folder and run:

`docker-compose up -d`

3. Wait a few minutes for Docker to finish the setup and go to http://localhost:8000/

4. Install WordPress with your desired credentials

5. Go to Plugins > Add New Plugin > Upload Plugin and select the archive "todo-list.zip"

6. Install and activate the plugin

7. Go to Settings > Permalinks and select "Post name" for your Permalink structure, then save the changes.

## Usage

### Task management

Manage tasks from the main left sidebar menu "ToDo List". There you can find a simple UI to create, edit and delete a task.

### Task display

You can output your tasks on the website by using:

- Shortcode:

`[todo-list title="My title"]`

- Guttenberg block: Use the block editor interface to add a block called "ToDo List". Specify a title from the right sidebar block menu.

## Future improvements

- Automate the WP installation setup with wp-cli
