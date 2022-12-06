<div>
    <x-input-label for="street" :value="__('Street')" />
    <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" value="{{ $user->address?->first()->street }}"  />
    <x-input-error class="mt-2" :messages="$errors->get('street')" />
</div>

<div>
    <x-input-label for="house_number" :value="__('House Number')" />
    <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block" value="{{ $user->address?->first()->house_number }}"  />
    <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
</div>

<div>
    <x-input-label for="locality" :value="__('Locality')" />
    <x-text-input id="locality" name="locality" type="text" class="mt-1 block w-full" value="{{ $user->address?->first()->locality }}"  />
    <x-input-error class="mt-2" :messages="$errors->get('locality')" />
</div>

<div>
    <x-input-label for="province" :value="__('Province')" />
    <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" value="{{ $user->address?->first()->province }}"  />
    <x-input-error class="mt-2" :messages="$errors->get('province')" />
</div>

<div>
    <x-input-label for="city" :value="__('City')" />
    <select name="city" id="city">
        <option value="CDMX" selected>Ciudad de México</option>
    </select>
</div>

<div>
    <x-input-label for="postal_code" :value="__('Postal Code')" />
    <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block" value="{{ $user->address?->first()->postal_code }}"  />
    <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
</div>