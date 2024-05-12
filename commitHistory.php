<?php
/**
* Plugin Name: commitHistory
* Plugin URI: https://www.test.com/
* Description: List commit history of a repository from GitHub
* Version: 0.1
* Author: Boris Cabrera
* Author URI: https://www.test.com/
**/


// Add a new menu option in the dashboard
function commit_history_menu() {
    add_menu_page(
        'Commit History Menu',
        'Commit History',
        'manage_options',
        'custom-dashboard-menu',
        'commit_history_page',
        'dashicons-admin-generic',
        4
    );
}
add_action('admin_menu', 'commit_history_menu');

// Callback function to display the UI
function commit_history_page() {
    ?>
    <div class="wrap">
        <h2>GitHub Repository Explorer</h2>
        <label for="username">Write user name:</label>
        <input type="text" id="username" name="username" required>
        <button onclick="loadRepositories()">Load Repositories</button>
        <div id="repository-section" style="display: none;">
            <label for="repository">Select a repository:</label>
            <select id="repository" name="repository">
                <!-- Options will be dynamically added here -->
            </select>
            <button onclick="loadCommitHistory()">Load Commit History</button>
        </div>
    </div>
    <script>
         function loadRepositories() {
            var username = document.getElementById('username').value;
            if (username.trim() !== '') {
                var apiUrl = 'https://api.github.com/users/' + username + '/repos';
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        var repositoryDropdown = document.getElementById('repository');
                        repositoryDropdown.innerHTML = ''; // Clear previous options

                        data.forEach(function(repo) {
                            var option = document.createElement('option');
                            option.text = repo.name;
                            option.value = repo.name;
                            repositoryDropdown.appendChild(option);
                        });

                        document.getElementById('repository-section').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error fetching repositories:', error);
                        alert('Error fetching repositories. Please try again later.');
                    });
            } else {
                alert('Please enter a username.');
            }
        }


        
    </script>
    <?php
}