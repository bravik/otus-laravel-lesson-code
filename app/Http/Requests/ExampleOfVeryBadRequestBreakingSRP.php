<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Подобная организация валидации нарушает принцип единственной ответственности вообще во всех смыслах, которые можно придумать.
 * - Request - это модель запроса, в нем не дожно быть никакой логики, только данные.
 * Однако Request Laravel - это как глобальная точка доступа к функциям приложения...
 * Можно вытащить user(), можно вытащить из контейнера сервис валидатора и запустить валидацию,
 * можно инжектить сервисы в authorize() и вообще делать с ним все что угодно.
 * Мнимое удобство использования за счет игнорирования лучших практик рарзработки.
 *
 * Что делать? В случае если сложная валидация, требующая обращения к БД или к другим сервисам,
 * лучше вынести ее в отдельный сервис и явно передавать туда запрос или данные для валидации.
 */
class ExampleOfVeryBadRequestBreakingSRP extends FormRequest
{
    /**
     * Добавляет в модель запроса поле где хранит модель!
     * Оно нужно только потому что требуется в rules(), но в rules() нельзя инжектить репозиторий
     */
    private Category $model;

    /**
     * Сюда прям в реквесте инжектится репозиторий! и идет обращение в БД.
     *
     * Далее из реквеста вытаскивается модель пользователя,
     * идет обращение к механизму Laravel Security и запускаются проверки из политики.
     * Какое это отношение имеет к модели реквеста?
     */
    public function authorize(CategoryRepository $repository): bool
    {
        $customer    = $this->user('trainer');
        $categoryId  = (int) $this->route()->parameter('cid', null);
        if ($categoryId){
            return $customer->can('update', $this->model);
        }
        throw new \RuntimeException('User is not authenticated as XXX.');
    }

    public function rules(): array
    {
        /**
         * Здесь мы работаем с моделью Eloquent, полученной в authorize()
         * Мы обращаемся к relation meals(), что триггерит запрос в БД под капотом!
         * После этого мы запускаем валидатор ларавель exists:{$connectionName},id
         * Который в свою очередь запускает еще один запрос в базу данных
         * И все это делается из объекта Request!
         */
        $mealModel  = $this->model->meals()->getModel();
        $connectionName = "{$mealModel->getConnectionName()}.{$mealModel->getTable()}";

        $rules = [
            'meal'     => "required|exists:{$connectionName},id",
            'weight'   => 'required|min:1|integer|max:10000',
            'is_snack' => 'required|boolean',
        ];

        return $rules;
    }
}
