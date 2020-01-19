<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en" >

<head>
    <!-- Editor Hint: ¿áéíóú -->
    <meta charset="utf-8" content="application/xhtml+xml" />
    <link rel="stylesheet" type="text/css" media="all" href="{urlFor name="home"}resources/bootstrap/css/bootstrap.min1.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="{urlFor name="home"}resources/css/style.1.css"/>
    <link rel="stylesheet" href="{urlFor name="home"}resources/css/jquery-ui.css"/>
    <link rel="stylesheet" href="{urlFor name="home"}resources/css/solas1.css"/>
</head>


<body>

{if isset($all_users) && count($all_users) > 0}

<table id="myTable" style="overflow-wrap: break-word; word-break:break-all;" class="container table table-striped">
  <thead>
    <th width="15%">Name</th>
    <th width="10%">Created</th>
    <th width="15%">Native Language</th>
    <th width="15%">Language Pairs</th>
    <th width="15%">Biography</th>
    <th width="15%">Certificates</th>
    <th width="15%">Email</th>
  </thead>

  <tbody>
  {foreach $all_users as $user_row}

    <tr>
      <td><a href="{urlFor name="user-public-profile" options="user_id.{$user_row['user_id']}"}" target="_blank">{TemplateHelper::uiCleanseHTML($user_row['name'])}</a>{$user_row['reviewed_text']}</td>
      <td>{$user_row['created_time']}</td>
      <td>{$user_row['native_language']}</td>
      <td>{$user_row['language_pairs']}</td>
      <td>{TemplateHelper::uiCleanseHTMLNewlineAndTabs($user_row['bio'])}</td>
      <td>{$user_row['certificates']}</td>
      <td>{$user_row['email']}</td>
    </tr>

  {/foreach}
  </tbody>

</table>

{else}<p class="alert alert-info">No Users</p>{/if}

</body>
</html>
