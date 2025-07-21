<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meatproduction Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .card { margin-bottom: 20px; }
        .form-section { margin-bottom: 30px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div id="app" class="container py-5">
        <h1 class="text-center mb-5">Meatproduction API Demo</h1>
        
        <!-- Токен и статус -->
        <div class="alert alert-info" v-if="token">
            <strong>Токен:</strong> <code>${token.substring(0, 15)}...</code>
            <button @click="logout" class="btn btn-sm btn-danger float-end">Выйти</button>
        </div>
        
        <!-- Регистрация -->
        <section class="form-section">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2>Регистрация</h2>
                </div>
                <div class="card-body">
                    <form @submit.prevent="register">
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input v-model="registerData.name" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input v-model="registerData.phone" type="tel" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Адрес</label>
                            <input v-model="registerData.address" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input v-model="registerData.password" type="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Подтверждение пароля</label>
                            <input v-model="registerData.password_confirmation" type="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                        <div v-if="registerMessage" class="mt-2" :class="registerSuccess ? 'success' : 'error'">
                            ${registerMessage}
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <!-- Вход -->
        <section class="form-section" v-if="!token">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h2>Вход</h2>
                </div>
                <div class="card-body">
                    <form @submit.prevent="login">
                        <div class="mb-3">
                            <label class="form-label">Телефон</label>
                            <input v-model="loginData.phone" type="tel" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input v-model="loginData.password" type="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Войти</button>
                        <div v-if="loginMessage" class="mt-2" :class="loginSuccess ? 'success' : 'error'">
                            ${loginMessage}
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <!-- Продукты -->
        <section class="form-section">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h2>Продукты</h2>
                </div>
                <div class="card-body">
                    <button @click="getProducts" class="btn btn-info mb-3">Получить продукты</button>
                    <div v-if="loadingProducts" class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <div v-if="productsError" class="error">${productsError}</div>
                    <div v-if="products.length" class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Цена</th>
                                    <th>Категория</th>
                                    <th>Наличие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in products" :key="product.id">
                                    <td>${product.name}</td>
                                    <td>${product.description}</td>
                                    <td>${product.price} ₽</td>
                                    <td>${product.category}</td>
                                    <td>${product.stock} шт.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Заказы -->
        <section class="form-section" v-if="token">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h2>Мои заказы</h2>
                </div>
                <div class="card-body">
                    <button @click="getOrders" class="btn btn-warning mb-3">Получить мои заказы</button>
                    <div v-if="loadingOrders" class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <div v-if="ordersError" class="error">${ordersError}</div>
                    <div v-if="orders.length">
                        <div v-for="order in orders" :key="order.id" class="card mb-3">
                            <div class="card-header">
                                Заказ #${order.id} - ${order.status} (${order.total} ₽)
                            </div>
                            <div class="card-body">
                                <p>${order.comment || 'Без комментария'}</p>
                                <h6>Товары:</h6>
                                <ul>
                                    <li v-for="item in order.items" :key="item.id">
                                        ${item.product.name} - ${item.quantity} × ${item.price} ₽
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Создание заказа -->
        <section class="form-section" v-if="token && products.length">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h2>Создать заказ</h2>
                </div>
                <div class="card-body">
                    <form @submit.prevent="createOrder">
                        <div class="mb-3">
                            <label class="form-label">Комментарий к заказу</label>
                            <input v-model="orderData.comment" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Товары</label>
                            <div v-for="(item, index) in orderData.items" :key="index" class="row mb-2">
                                <div class="col-md-6">
                                    <select v-model="item.product_id" class="form-select" required>
                                        <option value="">Выберите товар</option>
                                        <option v-for="product in products" :value="product.id" :key="product.id">
                                            ${product.name} (${product.price} ₽)
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input v-model.number="item.quantity" type="number" min="1" class="form-control" placeholder="Количество" required>
                                </div>
                                <div class="col-md-2">
                                    <button @click="removeOrderItem(index)" type="button" class="btn btn-outline-danger">×</button>
                                </div>
                            </div>
                            <button @click="addOrderItem" type="button" class="btn btn-sm btn-outline-primary">Добавить товар</button>
                        </div>
                        <button type="submit" class="btn btn-danger">Оформить заказ</button>
                        <div v-if="orderMessage" class="mt-2" :class="orderSuccess ? 'success' : 'error'">
                            ${orderMessage}
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        const { createApp, ref } = Vue;
        
        createApp({
            delimiters: ['${', '}'],
            setup() {
                const apiBaseUrl = 'http://meat.ru/api';
                
                // Состояние приложения
                const token = ref(localStorage.getItem('token') || '');
                const products = ref([]);
                const orders = ref([]);
                const loadingProducts = ref(false);
                const loadingOrders = ref(false);
                const productsError = ref('');
                const ordersError = ref('');
                
                // Данные форм
                const registerData = ref({
                    name: '',
                    phone: '',
                    address: '',
                    password: '',
                    password_confirmation: ''
                });
                const loginData = ref({
                    phone: '',
                    password: ''
                });
                const orderData = ref({
                    comment: '',
                    items: [{ product_id: '', quantity: 1 }]
                });
                
                // Сообщения
                const registerMessage = ref('');
                const registerSuccess = ref(false);
                const loginMessage = ref('');
                const loginSuccess = ref(false);
                const orderMessage = ref('');
                const orderSuccess = ref(false);
                
                // Методы
                const setAuthHeader = () => {
                    return { headers: { Authorization: `Bearer ${token.value}` } };
                };
                
                const register = async () => {
                    try {
                        const response = await axios.post(`${apiBaseUrl}/register`, registerData.value);
                        token.value = response.data.token;
                        localStorage.setItem('token', token.value);
                        registerMessage.value = 'Регистрация успешна!';
                        registerSuccess.value = true;
                    } catch (error) {
                        registerMessage.value = error.response?.data?.message || 'Ошибка регистрации';
                        registerSuccess.value = false;
                    }
                };
                
                const login = async () => {
                    try {
                        const response = await axios.post(`${apiBaseUrl}/login`, loginData.value);
                        token.value = response.data.token;
                        localStorage.setItem('token', token.value);
                        loginMessage.value = 'Вход выполнен успешно!';
                        loginSuccess.value = true;
                    } catch (error) {
                        loginMessage.value = error.response?.data?.message || 'Ошибка входа';
                        loginSuccess.value = false;
                    }
                };
                
                const logout = () => {
                    token.value = '';
                    localStorage.removeItem('token');
                    products.value = [];
                    orders.value = [];
                };
                
                const getProducts = async () => {
                    loadingProducts.value = true;
                    productsError.value = '';
                    try {
                        const response = await axios.get(`${apiBaseUrl}/products`);
                        products.value = response.data;
                    } catch (error) {
                        productsError.value = error.response?.data?.message || 'Ошибка загрузки продуктов';
                    } finally {
                        loadingProducts.value = false;
                    }
                };
                
                const getOrders = async () => {
                    if (!token.value) return;
                    
                    loadingOrders.value = true;
                    ordersError.value = '';
                    try {
                        const response = await axios.get(`${apiBaseUrl}/orders?user_id=1`, setAuthHeader());
                        orders.value = response.data;
                    } catch (error) {
                        ordersError.value = error.response?.data?.message || 'Ошибка загрузки заказов';
                    } finally {
                        loadingOrders.value = false;
                    }
                };
                
                const addOrderItem = () => {
                    orderData.value.items.push({ product_id: '', quantity: 1 });
                };
                
                const removeOrderItem = (index) => {
                    orderData.value.items.splice(index, 1);
                };
                
                const createOrder = async () => {
                    try {
                        const payload = {
                            user_id: 1, // В реальном приложении получаем из токена
                            items: orderData.value.items.filter(item => item.product_id),
                            comment: orderData.value.comment
                        };
                        
                        const response = await axios.post(`${apiBaseUrl}/orders`, payload, setAuthHeader());
                        orderMessage.value = `Заказ #${response.data.id} создан успешно!`;
                        orderSuccess.value = true;
                        orderData.value = { comment: '', items: [{ product_id: '', quantity: 1 }] };
                        getOrders();
                    } catch (error) {
                        orderMessage.value = error.response?.data?.message || 'Ошибка создания заказа';
                        orderSuccess.value = false;
                    }
                };
                
                // При загрузке страницы получаем продукты, если пользователь авторизован
                if (token.value) {
                    getProducts();
                }
                
                return {
                    token,
                    products,
                    orders,
                    loadingProducts,
                    loadingOrders,
                    productsError,
                    ordersError,
                    registerData,
                    loginData,
                    orderData,
                    registerMessage,
                    registerSuccess,
                    loginMessage,
                    loginSuccess,
                    orderMessage,
                    orderSuccess,
                    register,
                    login,
                    logout,
                    getProducts,
                    getOrders,
                    addOrderItem,
                    removeOrderItem,
                    createOrder
                };
            }
        }).mount('#app');
    </script>
</body>
</html>