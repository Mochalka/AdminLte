<?php
class subjectMap extends BaseMap {

  public function findAll($ofset = 0, $limit = 30) {
    $res = $this->db->query("SELECT subject.subject_id, subject.name, subject.hours, subject.active, otdel.name AS otdel"
    . " FROM subject INNER JOIN otdel ON subject.otdel_id=otdel.otdel_id LIMIT $ofset, $limit");
    return $res->fetchAll(PDO::FETCH_OBJ);
  }

  public function findById($id) {
    if ($id) {
      $res = $this->db->query("SELECT subject.subject_id, subject.name, subject.hours, subject.active, otdel.name AS otdel"
      . " FROM subject INNER JOIN otdel ON subject.otdel_id=otdel.otdel_id WHERE subject.subject_id=$id");
      return $res->fetchObject("Subject");
      }
      return new Subject();
  } 

  public function save(Subject $subject) {
    if ($subject->validate()) {
      if ($subject->subject_id == 0) {
      return $this->insert($subject);
      } else {
      return $this->update($subject);
      }
      }
      return false;
  }

  private function insert(Subject $subject) {
    $name = $this->db->quote($subject->name);
    if ($this->db->exec("INSERT INTO subject(name, otdel_id, hours)"
      . " VALUES($name, $subject->otdel_id, $subject->hours)") == 1) {
      $subject->subject_id = $this->db->lastInsertId();
    return true;
    }
    return false;
  }

  private function update(Subject $subject) {
    $name = $this->db->quote($subject->name);
    if ( $this->db->exec("UPDATE subject SET name = $name, otdel_id = $subject->otdel_id,"
    . " hours = $subject->hours WHERE subject_id = ".$subject->subject_id) == 1) {
      return true;
    }
    return false;
  }

  public function count() {
    $res = $this->db->query("SELECT COUNT(*) AS cnt FROM subject");
    return $res->fetch(PDO::FETCH_OBJ)->cnt;
  }

  public function findViewById($id) {
    if ($id) {
      $res = $this->db->query("SELECT subject.subject_id, subject.name, subject.hours, subject.active, otdel.name AS otdel"
      . " FROM subject INNER JOIN otdel ON subject.otdel_id=otdel.otdel_id WHERE subject_id=$id");
      return $res->fetch(PDO::FETCH_OBJ);
      }
      return false;
  }

  public function arrSubjects() {
    $res = $this->db->query("SELECT subject_id AS id, name AS value FROM subject");
    return $res->fetchAll(PDO::FETCH_ASSOC);
  }
}