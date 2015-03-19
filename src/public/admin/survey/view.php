<?php

require_once('../header.php');

function getSurvey($id) {
  $db = new Database();
  $db->query('SELECT * FROM surveys WHERE id = :id');
  $db->bind(':id', $id);
  return $db->single();
}

function getQuestions($id, $module = null) {
  $db = new Database();
  if ($module != null) {
    $db->query('SELECT * FROM questions WHERE survey_id = :survey_id AND module = :module');
    $db->bind(':module', $module);
  } else {
    $db->query('SELECT * FROM questions WHERE survey_id = :survey_id AND module IS NULL');
  }
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getStudents($id) {
  $db = new Database();
  $db->query('SELECT * FROM students WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

function getModules($id) {
  $db = new Database();
  $db->query('SELECT * FROM modules WHERE survey_id = :survey_id');
  $db->bind(':survey_id', $id);
  return $db->resultset();
}

$survey = getSurvey($_GET['id']);

?>

<div class="page-header">
<h2><?php echo $survey['title'] ?> <span class="small"><?php echo $survey['subtitle'] ?></span>
<a href="delete?id=<?php echo $survey['id']; ?>" class="btn btn-danger pull-right">Delete Survey</a></h2>
</div>

<div role="tabpanel">

  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#questions" aria-controls="questions" role="tab" data-toggle="tab">Questions</a></li>
    <li role="presentation"><a href="#students" aria-controls="students" role="tab" data-toggle="tab">Students</a></li>
    <li role="presentation"><a href="#modules" aria-controls="modules" role="tab" data-toggle="tab">Modules</a></li>
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="questions">
      <?php include('tpl_questions.php'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="students">
      <h2>Students</h2>
      <table id="survey-question-table" class="table">
        <thead>
          <tr>
            <th>Student ID</th>
            <th>Token</th>
            <th><a href="send?id=<?php echo $survey_id; ?>" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> Resend all</a></th>
          </tr>
        </thead>
        <tbody>
        <?php
        $students = getStudents($survey['id']);
        foreach ($students as $student) { ?>
            <tr>
              <td><?php echo $student['aber_id'] ?></td>
              <td><?php echo $student['token'] ?></td>
              <td></td>
            </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="modules">
      <h2>Modules</h2>
      <table id="survey-question-table" class="table">
        <thead>
          <tr>
            <th>Module Code</th>
            <th>Title</th>
            <th>Staff</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($modules as $module) { ?>
            <tr>
              <td><?php echo $module['module_code'] ?></td>
              <td><?php echo $module['title'] ?></td>
              <td><?php echo $module['module_code'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php require_once('../footer.php'); ?>