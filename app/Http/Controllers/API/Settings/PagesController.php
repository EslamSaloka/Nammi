<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\FAQ\FAQResource;

class PagesController extends Controller
{
    public function aboutIndex() {
        return (new \App\Support\API)->isOk(__("About Page"))->setData(getAboutPage())->build();
    }

    public function policyIndex() {
        return (new \App\Support\API)->isOk(__("Policy Page"))->setData(getPolicyPage())->build();
    }

    public function termsIndex() {
        return (new \App\Support\API)->isOk(__("Terms Page"))->setData(getTermsPage())->build();
    }

    public function FAQIndex() {
        return (new \App\Support\API)->isOk(__("FAQ Lists"))->setData(FAQResource::collection(getFAQPage()))->build();
    }
}
