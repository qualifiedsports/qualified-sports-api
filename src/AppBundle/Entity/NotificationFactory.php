<?php

namespace AppBundle\Entity;

/**
 * NotificationFactory
 */
class NotificationFactory
{
    static public function measurementAnomalyDetected(User $user, $fullName, $type, $value) {
        return (new Notification())
            ->setUser($user)
            ->setNotification("Pacjent $fullName wymaga konsultacji, anomalia dla $type, wartość $value.");
    }

    static public function measurementOfHeartRateAnomalyDetected(User $user, $value) {
        $moreOrLess = $value > 100 ? 'powyżej' : $value < 50 ? 'poniżej' : '';
        return (new Notification())
            ->setUser($user)
            ->setNotification("
                Wykryto anomalię związaną z tętnem spoczynkowym, które wynosi $moreOrLess wartości normatywnej 50-100 uderzeń/minutę.
                Twój lekarz został poinformowany o incydencie.
                Zgłoś się do lekarza medycyny sportowej i neurologa oraz odbyj wymienione poniżej badania:
                - Badanie D-dimer;
                - Badanie CKMB;
                - Badanie EKG Spoczynkowe;
                - Badanie EKG Holter;
                - Badanie UKG;
            ");
    }

    /**
     * @var $systolic Skurczowe
     * @var $diastolic Rozkurczowe
     */
    static public function measurementOfBloodPressureAnomalyDetected(User $user, $systolic, $diastolic) {
        $problem = '';
        if ($systolic > 150) {
            $problem .= ' skurczowe';
        };
        if ($diastolic > 90) {
            if (!empty($problem)) {
                $problem .= ' i';
            }
            $problem .= ' rozkurczowe';
        }
        return (new Notification())
            ->setUser($user)
            ->setNotification("
                Wykryto anomalię związaną z ciśnieniem tętniczy, zbyt wysokie ciśnienie $problem wynoszące $systolic/$diastolic, dla wartości normatywnej 150/90.
                Twój lekarz został poinformowany o incydencie.
                Zgłoś się do lekarza medycyny sportowej, okulisty, neurologa, laryngologa i fizjoterapeuty, oraz odbyj wymienione poniżej badania:
                - Badanie Ciśnienie Holter;
                - Badanie Spirometria;
                - Badanie Lipidogram;
                - Badanie TSH;
                - Badanie Kreatynina;
                - Badanie Morfologia;
            ");
    }

    static public function measurementOfFastingGlucoseAnomalyDetected(User $user, $value) {
        $moreOrLess = $value > 100 ? 'powyżej' : $value < 60 ? 'poniżej' : '';
        return (new Notification())
            ->setUser($user)
            ->setNotification("
                Wykryto anomalię związaną z glukozą na czczo, które wynosi $moreOrLess wartości normatywnej 60-100mg%.
                Twój lekarz został poinformowany o incydencie.
                Zgłoś się do lekarza medycyny sportowej, okulisty, laryngologa, fizjoterapeuty i neurologa oraz odbyj wymienione poniżej badania:
                - Badanie Test obciążenia glukozą;
            ");
    }

    static public function measurementOfRespiratoryAnomalyDetected(User $user, $value) {
        $moreOrLess = $value < 96 ? 'poniżej' : '';
        return (new Notification())
            ->setUser($user)
            ->setNotification("
                Wykryto anomalię związaną z niewydolnością układu oddechowego, które wynosi $moreOrLess wartości normatywnej 96%.
                Twój lekarz został poinformowany o incydencie.
                Zgłoś się do lekarza medycyny sportowej, trenerów i fizjoterapeuty oraz odbyj wymienione poniżej badania:
                - Badanie Spirometria spocznkowa;
                - Badanie Ergospirometria;
                - Badanie Oznaczenie mleczanów;
                - Badanie Morfologi;
                - Badanie CRP;
                - Badanie EKG Spoczynkowe;
            ");
    }
}
