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
    < class="wrap">
        <h2>GitHub Repository Explorer</h2>
        <label for="username">Write user name:</label>
        <input type="text" id="username" name="username" required>
        <button onclick="loadRepositories()">Load Repositories</button>
        
        <div id="repository-section" style="display: none;padding-top: 20px;padding-bottom: 20px;">
            <label for="repository">Select a repository:</label>
            <select id="repository" name="repository">
                <!-- Options will be dynamically added here -->
            </select>
            <button onclick="loadCommitHistory()">Load Commit History</button>
        </div>
        <div id="commit-history" style="display: none;">
            <!-- Table will created dynamically and added here -->
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


        function loadCommitHistory() {
            var selectedRepository = document.getElementById('repository').value;
            var username = document.getElementById('username').value;
            if (selectedRepository !== '') {
                var apiUrl = 'https://api.github.com/repos/' + username + '/' + selectedRepository + '/commits';
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {

                        var commitHistoryTable = document.createElement('table');
                        commitHistoryTable.className = 'commit-history-table';
                        commitHistoryTable.innerHTML = '<tr><th>Author</th><th>Date</th><th>Message</th></tr>';

                        data.forEach(function(commit) {
                            var commitRow = commitHistoryTable.insertRow();
                            var authorCell = commitRow.insertCell();
                            var dateCell = commitRow.insertCell();
                            var messageCell = commitRow.insertCell();

                            authorCell.textContent = commit.commit.author.name;
                            dateCell.textContent = new Date(commit.commit.author.date).toLocaleString();
                            messageCell.textContent = commit.commit.message;
                        });

                        var commitHistory = document.getElementById('commit-history');
                        commitHistory.innerHTML = ''; //Clear previous hostory
                        commitHistory.appendChild(commitHistoryTable);

                        document.getElementById('commit-history').style.display = 'block';

                    })
                    .catch(error => {
                        console.error('Error fetching commit history:', error);
                        alert('Error fetching commit history. Please try again later.');
                    });
            } else {
                alert('Please select a repository.');
            }
        }
    </script>


    <style>
        .commit-history-table {
            border-collapse: collapse;
            width: 100%;
        }

        .commit-history-table th, .commit-history-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .commit-history-table th {
            background-color: #f2f2f2;
        }
    </style>
    <?php
}