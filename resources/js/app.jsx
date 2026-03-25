import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom/client";
import axios from "axios";
import Installer from "./components/Installer";
import InstallerDatabase from "./components/InstallerDatabase";
import InstallerConfiguration from "./components/InstallerConfiguration";

const path = window.location.pathname;
const isStepTwo = path === "/installer/theme";
const isStepThree = path === "/installer/database";

function InstallerGuard() {
    const [checking, setChecking] = useState(true);

    useEffect(() => {
        axios.get("/api/installer/status")
            .then((response) => {
                if (response.data?.installed) {
                    window.location.href = response.data.redirect_to || "/admin/login";
                } else {
                    setChecking(false);
                }
            })
            .catch(() => {
                // If the status check fails, allow the installer to render
                setChecking(false);
            });
    }, []);

    if (checking) {
        return null;
    }

    if (isStepThree) return <InstallerConfiguration />;
    if (isStepTwo) return <InstallerDatabase />;
    return <Installer />;
}

ReactDOM.createRoot(document.getElementById("app")).render(
    <React.StrictMode>
        <InstallerGuard />
    </React.StrictMode>
);