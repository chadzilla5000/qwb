Author: Chad Buie
Date: April 26, 2023

Based on the code so far, the project is currently structured in a procedural style, without any clear separation of concerns. 

    - An MVC approach would help clean up the code and make it more modular and reusable.

Here's a high-level approach that you can take to refactor the code into an MVC structure:

Separate the view layer from the controller logic: Move all HTML files and any code responsible for rendering the user interface to a separate views directory. The controller logic should not be dependent on how the view is rendered.

Abstract the model layer: Create a separate models directory for all database-related code. This should include validation, querying, and any functions that interact directly with the database.

Create a controller layer - The controller layer should define the scheduling rules for interactions between the model, view and user action. 

Attach all interaction-related functions, including user input validation and triggering functions to get and send data via the model layer to the controller layer.

Create dependency injection mechanisms - Dependency injection will allow the application to remain decoupled by injecting only those models and views that are needed. 

    This can make future modifications easier as new dependencies are added without affecting the existing code.

Update code in the controllers, models, and views to work together using the above structure.

This approach will provide a clear separation of concerns for your application and make it easier to add new features in the future.