# crm-for-lessons
Аннотация: CRM система для небольших частных школ. Раннее данный проект выполнен в виде дипломной работы. Он был реализован в плохом виде: код невозможно развивать или разбираться. 

Цель данной работы: научиться на примере, и если получиться, показать пример использования ООП, учитывая полиморфизм, инкапсуляцию, наследование, а так же следовать некоторым паттернам программирования. 

Задачи данной CRM системы: 
1) связать взаимодействия пользователей и организации;
2) перевести бизнес-процессы организации из механического труда в машинный;
3) анализ и статистика некоторых аспектов организации.

Annotation: CRM system for small private schools. Earlier this project is in the form of a thesis. It was implemented in a bad way: the code cannot be developed or understood.

The purpose of this work: to learn by example, and if it works out, to set an example of using OOP, taking into account polymorphism, encapsulation, inheritance, as well as follow some programming patterns.

Tasks of this CRM system:
1) link user and organization interactions;
2) to translate the organization's business processes from mechanical to machine labor;
3) analysis and statistics of some aspects of the organization.

Инструкция по установке будет позже.

##change-log
###06.08.2019
#### Реализация паттерна Команда
Для начала реализовано 3 класса: 
- Operation:
методы use ($data) - запуск операции
- Command:
методы execute($data) - запуск команды; un_execute($data) - запуск обратной команды
- UserCommand extends User
методы Perform($data) - выполнить команду; undo() - отмена последней команды; redo() - возврат последней отмененной команды
Далее созданы наследники:
Operation->OperationAddLessons->OperationDeleteLessons
Command->CommandAddLessons
Command->CommandDeleteLessons
Примеры использования команд:
    <?php
        $user_command = new UserCommand();
		/*
		* @command и @lesson можно получить с фронта
		*/
		$id_schoolboys = json_encode([1,2,3]);
		$lesson = [
				'id_schoolboys' => $id_schoolboys,
				'id_teacher' => 5,
				'id_type' => 1,
				'start_datetime' => '2019-08-02 12:00:00',
				'end_datetime' => '2019-08-02 13:00:00',
				'id_room' => 1,
				'comment' => 'первый'
			];
		$data_lessons[0] = $lesson;
		$command = 'CommandAddLessons';
		
		/*
		* Вызов выполнения команды
		*/
		$user_command->perform(new $command(), $data_lessons);
		
		/*
		* Чтобы отменить:
		*/
		$user_command->undo();
		
		/*
		* Чтобы вернуть отмену
		*/
		$user_command->redo();
		
    ?>
    
Так же стоит добавить, что данные команд храняться в БД, а так же реализована проверка на пересечение уроков (чтобы исключить возможности накладки уроков после операции отмены и возврата)
#### Всякое вспомогательное
- class SQLRequest - для быстрых запросов в БД
- class User и class UserInitialization extends User - для работы с пользователями. Замечу, что в данном проекте пока нет задач реализовывать защиту (за исключением SQL инъекций), поэтому классы, связанные с соединением и пользователями пытаюсь делать самые простые.
#### Файловая система
Решено сделать 4 основных дирректории view, controller, model, settings
В settings 2 файла конфигурации:
- service-options.php - константы сервиса
- localizations.ru-RU.php - переменные со всеми сообщениями от сервиса пользователю. 



