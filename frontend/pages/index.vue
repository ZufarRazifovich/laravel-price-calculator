<script setup>
import { ref, onMounted, watch } from "vue";

const products = ref([]);
const countries = ref([]);

const selectedProduct = ref(null);
const selectedCountry = ref(null);
const taxNumber = ref("");

const result = ref(null);
const error = ref(null);
const pending = ref(false);
const isTaxNumberValid = ref(true);

onMounted(async () => {
  await loadProducts();
  await loadCountries();
  selectedProduct.value = products.value.length > 0 ? products.value[0] : null;
  selectedCountry.value = countries.value.length > 0 ? countries.value[0] : null;
});

const loadProducts = async () => {
  try {
    const response = await fetch("http://localhost:81/api/products");
    if (response.ok) {
      const data = await response.json();
      products.value = data.data;
    } else {
      const errorData = await response.json();
      error.value = errorData.error || "Failed to load products";
    }
  } catch (err) {
    error.value = "Network error or server is down";
  }
};

const loadCountries = async () => {
  try {
    const response = await fetch("http://localhost:81/api/countries");
    if (response.ok) {
      const data = await response.json();
      countries.value = data.data;
    } else {
      const errorData = await response.json();
      error.value = errorData.error || "Failed to load countries";
    }
  } catch (err) {
    error.value = "Network error or server is down";
  }
};

watch(selectedCountry, (newCountry) => {
  if (newCountry) {
    taxNumber.value = newCountry.code;
  } else {
    taxNumber.value = "";
  }
});

const handleTaxNumberInput = (event) => {
  const inputValue = event.target.value;
  const selectedCountryCode = selectedCountry.value ? selectedCountry.value.code : "";

  let maxNumericLength;
  if (selectedCountryCode === 'IT') {
    maxNumericLength = 11;
  } else {
    maxNumericLength = 9;
  }

  const numericValue = inputValue.slice(selectedCountryCode.length).replace(/\D/g, "");
  const truncatedValue = numericValue.slice(0, maxNumericLength);

  taxNumber.value = selectedCountryCode + truncatedValue;
  isTaxNumberValid.value = true;
};

const calculatePrice = async () => {
    if (!taxNumber.value || taxNumber.value.length < (selectedCountry.value ? (selectedCountry.value.code === 'IT' ? 13 : 11) : 0)) {
        isTaxNumberValid.value = false;
        return;
    }

    isTaxNumberValid.value = true;
    pending.value = true;
    error.value = null;
    result.value = null;

    try {
        const response = await fetch("http://localhost:81/api/calculate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                product_id: selectedProduct.value.id,
                tax_number: taxNumber.value,
            }),
        });

        if (response.ok) {
            const data = await response.json();
            result.value = data.data;
        } else {
            const errorData = await response.json();
            error.value = errorData.message || "An error occurred during calculation";
        }
    } catch (err) {
        error.value = "Network error or server is down";
    } finally {
        pending.value = false;
    }
};
</script>

<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
      <h1 class="text-xl font-bold text-center mb-4">
        Калькулятор стоимости продукта
      </h1>

      <form @submit.prevent="calculatePrice" class="space-y-4">
        <label class="block">
          <span class="text-gray-700">Выберите продукт:</span>
          <select
            v-model="selectedProduct"
            class="block w-full mt-1 p-2 border rounded"
          >
            <option
              v-for="product in products"
              :key="product.id"
              :value="product"
            >
              {{ product.name }} - {{ product.price }} €
            </option>
          </select>
        </label>

        <label class="block">
          <span class="text-gray-700">Выберите страну:</span>
          <select
            v-model="selectedCountry"
            class="block w-full mt-1 p-2 border rounded"
          >
            <option
              v-for="country in countries"
              :key="country.code"
              :value="country"
            >
              {{ country.name }}
            </option>
          </select>
        </label>

        <label class="block">
          <span class="text-gray-700">Введите tax номер:</span>
          <input
            type="text"
            :value="taxNumber"
            @input="handleTaxNumberInput"
            :placeholder="`Например ${selectedCountry ? selectedCountry.code : 'XX'}123456789`"
            class="block w-full mt-1 p-2 border rounded"
          />
          <div v-if="!isTaxNumberValid" class="mt-2 text-sm text-red-600">
            Пожалуйста, введите корректный TAX номер (9 цыфор).
          </div>
        </label>

        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
          :disabled="pending || !isTaxNumberValid"
        >
          {{ pending ? "Расчет..." : "Рассчитать" }}
        </button>
      </form>

      <div v-if="result" class="mt-4">
        <h2 class="text-lg font-semibold">Результат:</h2>
        <p>Продукт: {{ result.product_name }}</p>
        <p>Страна: {{ result.country_code }}</p>
        <p>Налоговая ставка: {{ result.tax_rate }}%</p>
        <p>Цена без налога: {{ result.price_without_tax }} €</p>
        <p>Сумма налога: {{ result.tax_amount }} €</p>
        <p>Итоговая цена: {{ result.total_price }} €</p>
      </div>
      <div v-if="error" class="mt-4 text-red-500">
        <p>Ошибка: {{ error }}</p>
      </div>
    </div>
  </div>
</template>