# Test task. Razoyo_CarProfile Module for Magento 2

The Razoyo_CarProfile module for Magento 2 allows customers to select a car from a pre-determined list and save their choice to their customer profile. This module adds a new menu item to the customer dashboard called "My Car," where customers can manage their car selection and view details of their selected car.

## Features

- **Car Selection**: Customers can select a car from the provided listCars API.
- **Profile Integration**: The selected car is saved to the customer's profile.
- **Car Details Display**: Displays an image and basic information about the selected car in the "My Car Profile" tab.
- **UI Flexibility**: Customers can change their selected car at any time through a user-friendly interface.

## Installation

### Prerequisites

- Magento 2 (latest supported version)
- Access to server and command line

### Steps

1. **Clone the repository**:
   ```bash
   cd <your_Magento_install_dir>/app/code
   mkdir Razoyo/CarProfile
   cd Razoyo/CarProfile
   git clone https://github.com/<your_github_username>/Razoyo_CarProfile.git .
   ```
2. **Enable the module:**
    ```bash
    php bin/magento module:enable Razoyo_CarProfile
    php bin/magento setup:upgrade
    ```
3. **Deploy static content (if in production mode):**
    ```bash
    php bin/magento setup:static-content:deploy
    ```
4. **Clear the cache:**
    ```bash
    php bin/magento cache:clean
    ```

## Usage

After installation, follow these steps to use the Razoyo_CarProfile module:

1. **Log In**: Sign into your customer account on the Magento store.
2. **Navigate to the Customer Dashboard**: Access the customer dashboard where you'll find a new menu item labeled "My Car".
3. **Select or Update Your Car**:
    - To select a car for the first time or change your existing selection, click on the "My Car" menu item.
    - Use the form provided to select a car from the dropdown list. This list is populated based on the `listCars` API.
    - Submit your selection to update your profile with your new car choice.

Your selection will be saved to your profile, and the car's image along with basic information (like make, model, year, price, MPG, and number of seats) will be displayed in the "My Car" tab.

## Screenshots

To help visualize the functionality and user interface of the Razoyo_CarProfile module, please refer to the screenshots included in the repository. These images showcase the car selection form and the display of car details on the customer dashboard.

