import React from "react";
import ReactDOM from "react-dom/client";
import Installer from "./components/Installer";
import InstallerDatabase from "./components/InstallerDatabase";
import InstallerConfiguration from "./components/InstallerConfiguration";

const path = window.location.pathname;
const isStepTwo = path === "/installer/theme";
const isStepThree = path === "/installer/database";

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        {isStepThree ? <InstallerConfiguration /> : isStepTwo ? <InstallerDatabase /> : <Installer />}
    </React.StrictMode>
);