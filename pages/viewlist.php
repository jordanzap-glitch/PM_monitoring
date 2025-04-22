<?php
// Start the session
include 'db_connection.php';
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree List - Green Eco Monitoring</title>
    <style>
        body {
            background-color: #e0f2e9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

        .logout-btn img {
            width: 40px;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2e7d32;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .view-btn {
            padding: 10px 14px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #1b5e20;
        }

        .survival-rate {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #2e7d32;
            font-size: 18px;
            font-weight: bold;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            width: 70%;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .search-btn {
            padding: 10px 20px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .search-btn:hover {
            background-color: #1b5e20;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>List of Trees</h1>
            <button class="logout-btn" onclick="logout()">
                <img src="Resources/logout.png" alt="Logout">
            </button>
        </div>

        <div class="search-container">
            <input type="text" id="searchSponsor" class="search-input" placeholder="Search by Sponsor's Name">
            <button class="search-btn" onclick="searchBySponsor()">Search</button>
        </div>

        <table id="plantTable">
            <thead>
                <tr>
                    <th>Tree Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Plant data will be dynamically inserted here -->
            </tbody>
        </table>

        <div class="survival-rate" id="survivalRate">

        </div>
    </div>

    <script