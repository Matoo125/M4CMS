  <!DOCTYPE html>
  <html>
    <head>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

      <style>
        body {
          background-color: green;
        }
      </style>
    </head>

    <body>

    <div class="container">
        
      <div class="card">

        <div class="card-content">

          <h2 class="center-align">Installation</h2>

            <h4>Database Information</h4>
             <form class="col s12" method="post" action="process.php">

               <div class="row">
                 <div class="input-field col s6">
                   <input id="database-host" name="database-host" type="text" class="validate">
                   <label for="database-host">Database Host</label>
                 </div>
                 <div class="input-field col s6">
                   <input id="database-name" name="database-name" type="text" class="validate">
                   <label for="database-name">Database Name</label>
                 </div>
               </div>

               <div class="row">
                 <div class="input-field col s6">
                   <input id="database-username" name="database-username" type="text" class="validate">
                   <label for="database-username">Database User</label>
                 </div>
                 <div class="input-field col s6">
                   <input id="database-password" name="database-password" type="text" class="validate">
                   <label for="database-password">Database Password</label>
                 </div>
               </div>


               <h4>User Information</h4>

               <div class="row">
                 <div class="input-field col s6">
                   <input id="user-email" name="user-email" type="email" class="validate">
                   <label for="user-email">Email</label>
                 </div>
                 <div class="input-field col s6">
                   <input id="user-username" name="user-username" type="text" class="validate">
                   <label for="user-username">Username</label>
                 </div>
               </div>

               <div class="row">
                 <div class="input-field col s6">
                   <input id="user-password" name="user-password" type="password" class="validate">
                   <label for="user-password">Password</label>
                 </div>
                 <div class="input-field col s6">
                   <input id="user-password2" name="user-password2" type="password" class="validate">
                   <label for="user-password2">Password Check</label>
                 </div>
               </div>


               <h4>Website Information</h4>

               <div class="row">
                 <div class="input-field col s12">
                   <input id="website-title" name="website-title" type="text" class="validate">
                   <label for="website-title">Title</label>
                 </div>
                 <div class="input-field col s12">
                   <textarea id="website-description" name="website-description" class="materialize-textarea"></textarea>
                   <label for="website-description">Description</label>
                 </div>
               </div>



               <button class="btn waves-effect waves-light" type="submit" name="action">Install
                 <i class="material-icons right">send</i>
               </button>

              </form>

           </div>

        </div>
      </div>

      <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/js/materialize.min.js"></script>
    </body>
  </html>