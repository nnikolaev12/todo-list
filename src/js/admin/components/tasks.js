const Tasks = (($) => {
  const getAllTasks = () => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "get_tasks",
      },
      success: function (response) {
        let container = $(".todo-list__tasks");
        const empty = $(".todo-list__tasks--empty");

        if (response.type === "error") {
          container.html("");
          empty.show();
          return;
        }

        container.html("");
        empty.hide();

        response.data.forEach((task) => {
          container.append(_getTaskTemplate(task));
        });
      },
    });
  };

  const toggleNewTaskForm = () => {
    $(".todo-list__add-btn").on("click", function () {
      $(".todo-list__add-task").slideToggle();
    });
  };

  const saveNewTask = () => {
    $(".todo-list__add-task form").on("submit", function (e) {
      e.preventDefault();

      let taskName, taskDescription, taskNonce;
      const formData = $(this).serializeArray();

      formData.forEach((field) => {
        if (field.name === "title") {
          taskName = field.value;
        } else if (field.name === "description") {
          taskDescription = field.value;
        } else if (field.name === "add_new_task") {
          taskNonce = field.value;
        }
      });

      _createTask(taskName, taskDescription, taskNonce);

      // empty inputs and hide form
      $("#tldAddTitle").val("");
      $("#tldAddDescription").val("");
      $(".todo-list__add-task").slideToggle();
    });
  };

  const toggleEditTaskForm = () => {
    $(".todo-list__tasks").on(
      "click",
      ".todo-list__tasks--item__edit",
      function () {
        const id = $(this).data("id");
        const title = $(this).parent().siblings().find("h3").text();
        const description = $(this).parent().siblings().find("p").text();

        // show form and fill inputs
        const form = $(".todo-list__edit-task");
        form.show();
        form.find("input[name='id']").val(id);
        form.find("input[name='title']").val(title);
        form.find("textarea").val(description);
      }
    );

    $(".todo-list__edit-task--close").on("click", function () {
      $(".todo-list__edit-task").hide();
    });
  };

  const updateTask = () => {
    $(".todo-list__edit-task form").on("submit", function (e) {
      e.preventDefault();

      let taskName, taskDescription, taskId, taskNonce;
      const formData = $(this).serializeArray();

      formData.forEach((field) => {
        if (field.name === "title") {
          taskName = field.value;
        } else if (field.name === "description") {
          taskDescription = field.value;
        } else if (field.name === "id") {
          taskId = field.value;
        } else if (field.name === "edit_new_task") {
          taskNonce = field.value;
        }
      });

      _updateTask(taskId, taskName, taskDescription, taskNonce);

      $(".todo-list__edit-task").hide();
    });
  };

  const deleteTask = () => {
    $(".todo-list__tasks").on(
      "click",
      ".todo-list__tasks--item__delete",
      function () {
        const id = $(this).data("id");
        if (confirm("Are you sure?")) {
          _deleteTask(id);
        }
      }
    );
  };

  /**
   * Private methods
   */
  const _createTask = (title, description, nonce) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "create_task",
        title: title,
        description: description,
        nonce: nonce,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError(response.message);
          return;
        }

        getAllTasks();
      },
    });
  };

  const _updateTask = (id, title, description, nonce) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "update_task",
        id: id,
        title: title,
        description: description,
        nonce: nonce,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError(response.message);
          return;
        }

        getAllTasks();
      },
    });
  };

  const _deleteTask = (id) => {
    $.ajax({
      url: ajaxurl,
      type: "POST",
      dataType: "json",
      data: {
        action: "delete_task",
        id: id,
      },
      success: function (response) {
        if (response.type === "error") {
          _throwError();
          return;
        }

        getAllTasks();
      },
    });
  };

  const _getTaskTemplate = (task) => {
    return `
    <li class="todo-list__tasks--item">
      <div>
        <h3>${task.title}</h3>
        <p>${task.description}</p>
      </div>
      <div>
        <button class="todo-list__btn todo-list__btn--primary todo-list__tasks--item__edit" data-id="${task.id}">Edit</button>
        <button class="todo-list__btn todo-list__btn--secondary todo-list__tasks--item__delete" data-id="${task.id}">Delete</button>
      </div>
    </li>
    `;
  };

  const _throwError = (message) => {
    alert(message || "Something went wrong. Please try again.");
  };

  return {
    init: () => {
      getAllTasks();

      toggleNewTaskForm();
      saveNewTask();

      toggleEditTaskForm();
      updateTask();

      deleteTask();
    },
  };
})(jQuery);
