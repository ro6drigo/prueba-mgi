# 📘 Información Técnica

## 🧱 Aplicación de los Principios SOLID

Se aplicán los principios SOLID de la siguiente manera:

-   **Single Responsibility Principle**:
    Cada clase y servicio tiene una única responsabilidad. Ejemplos:

    -   `StoreEventService`: Encargado exclusivamente de almacenar eventos y actualizar el estado del contenedor.
    -   `ResolveContainerStateService`: Responsable únicamente de determinar el estado confiable de un contenedor.
    -   `UpdateStaleContainerStatesService`: Dedicado a actualizar el estado de contenedores con eventos recientes.

-   **Open/Closed Principle**:
    Los servicios están diseñados para poder ser extendidos sin modificar su código base. Por ejemplo, se pueden agregar nuevas estrategias de resolución de estado implementando la interfaz `ResolvesContainerStateContract`.

-   **Liskov Substitution Principle**:
    Cada servicio implementa una interfaz (`StoresEventsContract`, `ResolvesContainerStateContract`, `UpdatesStaleContainersContract`), lo que permite intercambiar implementaciones sin afectar al resto del sistema. Esto también simplifica los tests, donde se pueden usar mocks sin romper la lógica.

-   **Interface Segregation Principle**:
    Las interfaces están claramente separadas y enfocadas en responsabilidades específicas. Cada servicio implementa su propia interfaz sin verse obligado a depender de métodos innecesarios.

-   **Dependency Inversion Principle**:
    Los controladores y otros servicios no dependen directamente de instancias concretas, sino de interfaces, lo cual facilita el desacoplamiento, la extensibilidad y las pruebas unitaria a través de mocks o stubs.

## 🧠 Estrategia de Razonamiento del "Estado Real"

La lógica para determinar el estado real de un contenedor está basada en la siguiente lógica:

1. Ventana de eventos recientes:
   Solo se consideran eventos dentro de la última hora (ACCEPTABLE_HOURS) como válidos para determinar el estado actual.

2. Quórum por fuente confiable:
   Se requiere un mínimo de 3 fuentes diferentes que coincidan en el mismo estado dentro de los últimos 30 minutos (QUORUM_ACCEPTABLE_MINUTES) para considerar un estado como "confiable".

3. Fallback de último evento:
   Si no hay quórum suficiente, se toma el estado del evento más reciente dentro del rango aceptable.

Esto ayuda a que el sistema sea más tolerante a errores, se adapte a datos inconsistentes y refleje el estado más probable, sin depender de una única fuente.

---

# 🚀 Cómo Levantar el Proyecto

## 🛠️ 1. Requisitos Previos

Asegurarse de tener instalados los siguientes programas en el sistema:

-   Docker y Docker Compose

## 📥 2. Clonar el Repositorio

```sh
git clone https://github.com/ro6drigo/prueba-mgi.git
cd prueba-mgi
```

## 🐳 3. Levantar los contenedores con Docker

La primera vez tenemos que construir los contenedores

```sh
docker-compose build
```

Luego iniciar los contenedores como siempre (-d detached mode: ejecuta los contenedores en background)

```sh
docker-compose up -d
```

Esto iniciará los contenedores de **PHP** y **MongoDB**.

Cuando necesites detener los contendores, puedes hacerlo con el siguiente comando:

```sh
docker-compose down
```

## 📦 4. Instalar Dependencias

```sh
docker exec -it app composer install
# o
composer install
```

Esto instalará todas las dependencias necesarias en el contenedor de PHP.

## ⚙️ 5. Configurar Variables de Entorno

```sh
docker exec -it app cp .env.example .env
docker exec -it app php artisan key:generate
# o
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` para configurar la base de datos y otras variables necesarias.

```sh
# .env
DB_CONNECTION=mongodb
DB_HOST=mongo
DB_PORT=27017
DB_DATABASE=prueba_mgi
DB_USERNAME=
DB_PASSWORD=
```

---

# 📡 Documentación de la API

## 🚀 Acceso a la Documentación

Puedes acceder a la documentación técnica de la API a través de la siguiente URL: `http://127.0.0.1:8000/api/documentation`

## 🎟 Endpoints disponibles

Documentación completa disponible en `/api/documentation`.

-   `POST /api/events`: Registrar un nuevo evento de estado.
-   `GET /api/containers`: Listar todos los contenedores con su estado verificado.
-   `GET /api/containers/{id}/status`: Consultar el estado confiable de un contenedor.

## ⚙️ Generar la Documentación

Si necesitas regenerar la documentación, puedes hacerlo con el siguiente comando:

```sh
docker exec -it app php artisan l5-swagger:generate
# o
php artisan l5-swagger:generate
```

Este comando actualizará la documentación basada en los cambios recientes en los controladores y rutas de la API.

## 📌 Notas

-   Asegúrate de que la aplicación esté en ejecución antes de acceder a la documentación.

-   Si la documentación no se actualiza, intenta limpiar la caché con:

    ```sh
    docker exec -it app php artisan config:clear && php artisan cache:clear
    # o
    php artisan config:clear && php artisan cache:clear
    ```

---

# 🔍 Estrategia para determinar el estado confiable

Se combinan tres estrategias:

-   **Quórum**: Se requieren al menos 3 eventos coincidentes en los últimos 30 minutos para aceptar un estado.
-   **Last update**: Si no se alcanza quórum, se toma el evento más reciente de la última hora.
-   **Timeout**: Si no hay eventos recientes, se asume como estado `unknown`.

---

# ✅ Pruebas

```sh
docker exec -it app php artisan test
# o
php artisan test
```

Si necesitas poder visualizar el coverage de los test, puedes hacerlo con el siguiente comando:

```sh
docker exec -it app php artisan test --coverage
# o
php artisan test --coverage
```
